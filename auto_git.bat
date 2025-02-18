@echo off
cd /d "C:\xampp\htdocs\AngeloCheese"
git add .
git commit -m "Auto update: %date%"
git push origin main
exit
