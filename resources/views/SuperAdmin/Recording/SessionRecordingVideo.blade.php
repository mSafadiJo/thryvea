<html>

<head>

    <link
            rel="stylesheet"
            href="//cdn.jsdelivr.net/npm/semantic-ui@2.4.1/dist/semantic.min.css"
    />
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/rrweb-player@latest/dist/style.css"
    />
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/rrweb@latest/dist/rrweb.min.css"
    />

    <script src="{{ asset('js/jquery-3.5.1.min.js') }}" type="text/javascript"></script>
    <meta http-equiv="Content-Security-Ploicy" content="upgrade-insecure-requests">
    <script src="https://cdn.jsdelivr.net/npm/rrweb@latest/dist/rrweb.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/rrweb-player@latest/dist/index.js"></script>
</head>
<body>






<div class="row">

    <div class="col-lg-6 rrweb-player">

    </div>

</div>
<?php

$event = [];
foreach ($Eventreq as $Events)
{
    $event = array_merge($event, unserialize($Events->event)) ;
}
?>
<script type="text/javascript">

    const events = <?php echo json_encode($event); ?>;
    const replayer = new rrwebPlayer({
            target: document.getElementsByClassName("rrweb-player")[0],
            data: {
                events,
                autoPlay: false,
            }
        }
    );

</script>
</body>
</html>

