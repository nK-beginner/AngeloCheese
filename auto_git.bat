@echo off
cd /d "C:\xampp\htdocs\Angelo\public\AngeloCheese"
git add .
git commit -m "Auto update: %date%"
git push origin main
exit
