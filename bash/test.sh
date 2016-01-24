#!/bin/sh

for (( i=0; i<$1; i++))
do
{
    sleep 1;curl 'http://phptest/login?id=2'
} &
done
wait