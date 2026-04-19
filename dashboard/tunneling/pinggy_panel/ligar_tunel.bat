@echo off
title Brotherhood Tunneling Control
echo Iniciando Tunelagem...

ssh -p 443 -R0:127.0.0.1:8080 -L4300:127.0.0.1:4300 -o StrictHostKeyChecking=no -o ServerAliveInterval=30 jc4eUYkAA0C@br.free.pinggy.io
