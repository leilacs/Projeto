#!/bin/bash

SALT=`echo -n "RXYG3aYWGvXU2joND5jghpyEUj60G6lS" | sha512sum | awk '{print $1}'`
echo -n $SALT$1 | sha512sum | awk '{print $1}'
