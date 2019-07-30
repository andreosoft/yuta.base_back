#!/bin/bash

kill $(ps aux | grep 'php console.php' | awk '{print $2}')

