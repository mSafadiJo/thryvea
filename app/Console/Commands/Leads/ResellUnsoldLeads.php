<?php

namespace App\Console\Commands\Leads;

use Illuminate\Console\Command;

use App\LeadsCustomer;
use App\Service_Campaign;
use App\Campaign;
use App\Services\ServiceQueries;
use App\Services\CrmApi;
use App\Services\ApiMain;

use Slack;
use Carbon\Carbon;

class ResellUnsoldLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:resell_unsold';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Try daily to resell unsold leads created within the begninning of the day';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Slack::send("Task: leads:resell_unsold\nExcecution Started");
        $psTimezone = new \DateTimeZone('America/Los_Angeles');

        $startDate = new \DateTime('now', $psTimezone);
        $startDate = $startDate->setTime(0,0,0)->format('Y-m-d H:i:s');

        $endDate = new \DateTime('now', $psTimezone);
        $endDate = $endDate->setTime(12, 59, 59)->format('Y-m-d H:i:s');

        // Query for unsold leads
        $queryStartTime = Carbon::now();
        $leads = LeadsCustomer::select('leads_customers.*')
                                ->leftJoin('campaigns_leads_users', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
                                ->whereNull('campaigns_leads_users.lead_id')
                                ->whereNotIn(
                                    'leads_customers.lead_fname',
                                    ['test', 'testing', 'Test']
                                )
                                ->whereNotIn(
                                    'leads_customers.lead_lname',
                                    ['test', 'testing', 'Test']
                                )
                                ->where('leads_customers.is_test', 0)
                                ->where('leads_customers.flag', null)
                                ->where('leads_customers.is_duplicate_lead', "!=", 1)
                                ->whereBetween('leads_customers.created_at', [$startDate, $endDate])
                                ->get();

        $queryEndTime = Carbon::now();

        Slack::send("Query Time: {$queryStartTime->diffInSeconds($queryEndTime)}");

        $results = [];
        $leadIds = [];
        foreach ($leads as $lead) {
            array_push($leadIds, $lead->lead_id);

            $address = $lead->addressMapping();
            $leadData = $lead->dataAsUnifiedJson();

            // // Get aged campaigns

            // We will get all the potential aged campaigns per lead as shared and exclusive

            // Direct campaigns exclusive and shared
            $campaignsType = 'aged-campaigns';

            $exclusiveDirectCampaigns = Service_Campaign::serviceCampaigns(campaignsType: $campaignsType, lead: $lead);
            $sharedDirectCampaigns = Service_Campaign::serviceCampaigns(campaignsType: $campaignsType, lead: $lead, shared: true);

            // Ping-Post campaigns shared and exclusive
            $exclusivePingPostCampaigns = Service_Campaign::serviceCampaigns(campaignsType: $campaignsType, lead: $lead, onlyPings: true);
            $sharedPingPostCampaigns = Service_Campaign::serviceCampaigns(campaignsType: $campaignsType, lead: $lead, shared: true, onlyPings: true);

            // We will merge both Direct and PingPost campaigns ids to get their total leads and bids and compare it with their capacity and budget period.
            $sharedCampaignIds = array_merge(
                $sharedDirectCampaigns->pluck('campaign_id')->toArray(),
                $sharedPingPostCampaigns->pluck('campaign_id')->toArray()
            );

            $exclusiveCampaignIds = array_merge(
                $exclusiveDirectCampaigns->pluck('campaign_id')->toArray(),
                $exclusivePingPostCampaigns->pluck('campaign_id')->toArray()
            );
            
            // Campaign::totalLeadsAndBids will get us total leads received based on budget_period and total bids per each period and lead type as shared or not. We combine them into three indexes for each period so we can compare them afterward in another function.
            $exclusive_keys = array(
                'leadsCampaignsDailiesExclusive' => 'daily',
                'leadsCampaignsWeeklyExclusive' => 'weekly',
                'leadsCampaignsMonthlyExclusive' => 'monthly',
            );

            $exclusiveCapsPerCampaign = [];

            // Exclusive total leads and bids
            foreach($exclusive_keys as $indexName => $budgetPeriod) {
                $totalLeadsAndBids = Campaign::totalLeadsAndBids($sharedCampaignIds, budgetPeriod: $budgetPeriod, shared: false);
                
                $campaignAsIndex = json_decode($totalLeadsAndBids, true);

                $exclusiveCapsPerCampaign[$indexName] = $campaignAsIndex;
            };

            // Shared total leads and bids
            $shared_keys = array(
                'leadsCampaignsDailiesShared' => 'daily',
                'leadsCampaignsWeeklyShared' => 'weekly',
                'leadsCampaignsMonthlyShared' => 'monthly',
            );

            $sharedCapsPerCampaign = [];
            foreach($shared_keys as $indexName => $budgetPeriod) {
                $totalLeadsAndBids = Campaign::totalLeadsAndBids($sharedCampaignIds, budgetPeriod: $budgetPeriod, shared: true);
                
                $campaignAsIndex = json_decode($totalLeadsAndBids, true);

                $sharedCapsPerCampaign[$indexName] = $campaignAsIndex;
            }

            // Now we have two payloads inside $exclusiveCapsPerCampaign and $sharedCapsPerCampaign contains current total capacity and total bids per aged campagins, one for exclusive and other for shared.

            // filterCampaign_* functions is to filter campaigns based on totalLeadsAndBids payload.

            $main_api_file = new ApiMain();

            $agedDirectCampaignsExclusive = $main_api_file->filterCampaign_exclusive_sheared_new_way(
                $exclusiveDirectCampaigns,
                $leadData,
                5,
                1,
                $exclusiveCapsPerCampaign,
                $sharedCapsPerCampaign
            );

            $agedDirectCampaignsShared = $main_api_file->filterCampaign_exclusive_sheared_new_way(
                $sharedDirectCampaigns,
                $leadData,
                10,
                2,
                $exclusiveCapsPerCampaign,
                $sharedCapsPerCampaign
            );

            $agedPingPostCampaignsShared = $main_api_file->filterCampaign_ping_post_new_way2(
                $sharedPingPostCampaigns,
                $leadData,
                1,
                0,
                $exclusiveCapsPerCampaign,
                $sharedCapsPerCampaign
            );
            
            $agedPingPostCampaignsExclusive = $main_api_file->filterCampaign_ping_post_new_way2(
                $exclusivePingPostCampaigns,
                $leadData,
                2,
                0,
                $exclusiveCapsPerCampaign,
                $sharedCapsPerCampaign
            );

            // Get PingPost campaigns bids by pinging them.
            $crmAPI = new CrmApi();

            $pingsApiResponsesShared = $crmAPI->send_multi_ping_apis($agedPingPostCampaignsShared['response']);
            $pingsApiResponsesExclusive = $crmAPI->send_multi_ping_apis($agedPingPostCampaignsExclusive['response']);
            
            
            // Sort PingPost campaigns bids to get highest bidders for both exclusive and shared.

            $campaignsShared = array_merge(
                $agedDirectCampaignsShared['campaigns'],
                $pingsApiResponsesShared['campaigns']
            );

            $campaignsExclusive = array_merge(
                $agedDirectCampaignsExclusive['campaigns'],
                $pingsApiResponsesExclusive['campaigns']
            );

            $campaignsShared = collect($campaignsShared);
            $campaignsShSorted = $campaignsShared->sortByDesc(function ($campaign) {
                return $campaign->campaign_budget_bid_shared;
            });

            $campaignsExclusive = collect($campaignsExclusive);
            $campaignsExSorted = $campaignsExclusive->sortByDesc(function ($campaign) {
                return $campaign->campaign_budget_bid_exclusive;
            });

            $pingPostExclusiveAndShared = array_merge(
                $pingsApiResponsesShared['response'],
                $pingsApiResponsesExclusive['response']
            );

            $first_one = 0;
            while (1) {
                try {
                    $data_from_post_lead = $main_api_file->post_and_pay($campaignsShSorted, $campaignsExSorted, $leadData, $pingPostExclusiveAndShared, null, $first_one);
                } catch (Exception $e) {
                    Slack::send("Exception: " . $e->getMessage());
                    continue;
                }

                if(!empty($data_from_post_lead)){
                    if( $data_from_post_lead['success'] == "false" ){
                        $first_one = $data_from_post_lead['first_one'];
                        $campaigns_sh_sorted = $data_from_post_lead['campaigns_sh_sorted'];
                        $campaigns_ex_sorted = $data_from_post_lead['campaigns_ex_sorted'];
                        $data_msg = $data_from_post_lead['data_msg'];
                    } else {
                        $data_msg = $data_from_post_lead['data_msg'];
                        break;
                    }
                } else {
                    break;
                }
            }
        }

        $leadIdsJoined = implode(", ", $leadIds);
        $leadIdsJoined = implode(", ", $leadIds);
        $leadCount = count($leadIds);

        Slack::send("{$leadCount} Leads been processed: {$leadIdsJoined}");

    }
}