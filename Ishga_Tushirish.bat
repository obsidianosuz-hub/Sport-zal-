@echo off
color 0b
echo ==========================================
echo       SPORT ZAL LOYIHASI SERVERI
echo ==========================================
echo.
echo Dastur ishga tushmoqda, iltimos kuting...

cd /d "d:\loyhalar\fashion\Spot zal\sport zal\Sport Zal"

:: Saytni brauzerda ochish
start http://127.0.0.1:8000

:: Check if port 8000 is already in use
netstat -ano | findstr :8000 >nul
if %errorlevel% equ 0 (
    echo.
    echo Server allaqachon ishlayapti! (http://127.0.0.1:8000)
    echo Dasturga bemalol kiraverishingiz mumkin.
) else (
    echo.
    echo Server yondirildi! Oynani yopmang!
    php artisan serve
)

pause
