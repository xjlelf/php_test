#!/bin/bash


function work_start()
{
    $1
}

function work_stop()
{
    local cmd
    cmd="$1 --action=stop"
    $cmd
}

while [ $# -gt 0 ];do
    case $1 in
        '--action')
        shift
        case $1 in
            'start')
                action="start"
            ;;
            'stop')
                action="stop"
            ;;
            *)
                echo "invalid action ${1} "
                exit
            ;;
        esac
        ;;
        *)
            echo "noting happen"
            exit
        ;;
    esac
    shift
done

cmd="php lib/queue/bin/monitor.php "

case $action in
    'start')
        work_start "$cmd"
    ;;
    'stop')
        work_stop "$cmd"
    ;;
    *)
        echo "invalid action ${1} ";
        exit
    ;;
esac
