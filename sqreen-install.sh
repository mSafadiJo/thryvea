#!/bin/bash

EXCEPTION_URL="https://back.sqreen.io/sqreen/v0/app_sqreen_exception"

ERROR_MESSAGE="An error occured during the installation."
DEB_CLOUD_PACKAGE_URL="https://8dc0b36f0ea6f2f21b721765e10a7e02768cd1825b4551f4:@packagecloud.io/install/repositories/sqreen/sqreen/script.deb.sh"
RPM_CLOUD_PACKAGE_URL="https://8dc0b36f0ea6f2f21b721765e10a7e02768cd1825b4551f4:@packagecloud.io/install/repositories/sqreen/sqreen/script.rpm.sh"

TEMP_FILE=$(mktemp /tmp/sqreen-php-install.script.XXXXXX)

exec >  >(tee -ia ${TEMP_FILE})
exec 2> >(tee -ia ${TEMP_FILE} >&2)

set -Eeu
set -o pipefail

trap 'failure "unknown" "The installation was interrupted.  Please contact us on https://sqreen.com/ and send us the log of the installation."' ERR

DOC_OTHER_INSTALL="Please, use the installation guide that suits your platform: https://docs.sqreen.com/sqreen-for-php/getting-started-in-php/#installation-guides"

function usage {
    printf "\
Usage: ${0} YOUR_SQREEN_TOKEN [YOUR_APP_NAME]

Your Sqreen token is available on your dashboard https://my.sqreen.com
" >&2
}

function failure {
  local readonly step=$1
  local readonly message=$2
  echo "ERROR: [On ${step}] $message"
  local readonly data=$(cat << EOF
{
  "klass": "PHPInstall",
  "message": "[${step}] ${message}",
  "context": {
    "uname": "$(uname -a)",
    "os": "$(cat /etc/*release 2>/dev/null | sed "s/\"/'/g" | sort | uniq | tr "\n" '|')",
    "install": "$(base64 -w 0 $TEMP_FILE)"
  }
}
EOF
)

  # Sending error report to Sqreen
  curl -sS ${EXCEPTION_URL} -d "${data}" -H 'Content-Type: application/json' -H "x-api-key: ${TOKEN}" > /dev/null
  exit 1
}

# This function should be able to tell us if we're inside a container.
# TODO: support containers other than docker (lxc?).
function running_in_container {
    # Method 1: /.dockerenv is present on most docker containers.
    # Method 2: some cgroups for PID 1 will reference the docker container.
    if [ -f "/.dockerenv" ] || grep -Eqs -m 1 ":/docker/" /proc/1/cgroup; then
        return 0
    fi
    return 1
}

function ask_sudo {
  if [ $EUID != 0 ]; then
    echo "This script must run with root privileges."
    sudo bash "$0" "$@" $TOKEN "$APP_NAME"
    exit $?
  fi
}

function separate_auth_conf {
    local AUTH_ENTRY=$( sed -E -n < /etc/apt/sources.list.d/sqreen_sqreen.list \
                      -e "s|^deb https?://([0-9a-f]*):([^@]*)@([^/]*)/.*$|machine \3 login \1 password \2|p" )

    if [[ -n "$AUTH_ENTRY" ]]
    then
        if
            sed -E < /etc/apt/sources.list.d/sqreen_sqreen.list \
                -e "s|^(.* https?://)([0-9a-f]*):([^@]*)@(.*)$|\1\4|" \
            > /etc/apt/sources.list.d/sqreen_sqreen.list.tmp
        then
            echo "Configuring authentication to Sqreen's APT source."
            echo "$AUTH_ENTRY" > /etc/apt/auth.conf.d/sqreen_sqreen
            mv -f /etc/apt/sources.list.d/sqreen_sqreen.list.tmp /etc/apt/sources.list.d/sqreen_sqreen.list
        else
            echo >&2 "Warning: could not configure APT authentication. apt update may complain in the future."
        fi
    fi
}

function install {
    if [ -f /etc/debian_version ]; then
        echo "Detected Debian operating system"
        curl -sS $DEB_CLOUD_PACKAGE_URL | bash

        if [[ -e /etc/apt/auth.conf.d/ ]]
        then
            echo "You system's apt supports login configuration file for APT sources and proxies."
            separate_auth_conf
        fi

        echo "Installing packages sqreen-agent and sqreen-php-extension"
        apt-get install -y sqreen-agent sqreen-php-extension
    elif [ -f /etc/redhat-release ] || [ -n "$(grep fedora /etc/os-release 2>/dev/null)" ] ; then
        echo "Found Red Hat operating system"
        curl -sS $RPM_CLOUD_PACKAGE_URL | bash
        echo "Installing packages sqreen-agent and sqreen-php-extension"
        yum install -y sqreen-agent sqreen-php-extension
    else
        failure "install" "Your platform is not supported by this installer at the moment. Please, use the manual installation guide: https://docs.sqreen.io/sqreen-for-php/manual-installation-of-the-php-extension/"
    fi
}

function check_install {
    if ! [ -x "$(command -v sqreen-installer)" ]; then
        failure "check_install" "sqreen-php-extension did not succeed to install. ${DOC_OTHER_INSTALL}"
    fi;

    if ! [ -x "$(command -v sqreen-agent)" ]; then
        failure "check_install" "sqreen-agent did not succeed to install. ${DOC_OTHER_INSTALL}"
    fi;

    # If we're running inside a container don't check if the daemon is running
    # as we expect the extension to launch it.
    if running_in_container; then
        return 0
    fi

    local -i install_ok=1
    for f in /proc/[0-9]*/comm; do
        if [[ "$(< $f)" = "sqreen-agent" ]]; then
            install_ok=0
            break
        fi
    done
    if [ $install_ok -ne 0 ]; then
        failure "check_install" "sqreen-agent is not running. ${DOC_OTHER_INSTALL}"
    fi
}

# 0 Transform long options to short options
# (getopts does not support long options)
for arg in "$@"; do
    shift
    case "$arg" in
        --help)
            set -- "$@" "-h"
            ;;
        --*)
            printf "${0}: Unknown option $arg\n" >&2
            usage
            exit 1
            ;;
        *)
            set -- "$@" "$arg"
            ;;
    esac
done

# 1 Check arguments
while getopts "h" opt; do
    case "$opt" in
        "h")
            usage
            exit 0
            ;;
        ?)
            usage
            exit 1
            ;;
    esac
done
shift $(( $OPTIND - 1 ))
case "$#" in
    0)
        printf "${0}: No token was passed to the install script.\n" >&2
        usage
        exit 1
        ;;
    1|2)
        TOKEN=$1
        APP_NAME=${2:-}
        ;;
    *)
        printf "${0}: Too many arguments are given to the install script.\n" >&2
        usage
        exit 1
        ;;
esac

# Perform a rough sanity check on token, to spot basic mistakes like empty string or argument swap.
if [[ ! "$TOKEN" =~ ^((env_org_[0-9a-fA-F]{56})|(org_[0-9a-fA-F]{60})|([0-9a-fA-F]{64}))$ ]]
then
    printf "${0}: Installation script expects a valid token as first argument.  The following does not look like a valid Sqreen token: \"${TOKEN}\"\n"
    exit 1
fi

# 2 Check root
ask_sudo

# Initialize installation logs
echo "" > ${TEMP_FILE}

# 3. Install sqreen
install

# 4. Check installation
check_install

# 5. Configure sqreen
sqreen-installer config $TOKEN "$APP_NAME"

rm ${TEMP_FILE}

echo ""
echo "================================================================================"
echo "================================================================================"
echo ""
echo "Thanks for installing Sqreen! You only have 2 steps left to complete your setup:"
echo "  1. Restart your Apache or FPM server."
echo "  2. Query a PHP page on your server."
echo ""
echo "Check your Sqreen dashboard: https://my.sqreen.com"
echo ""
