@echo off 

ECHO Teste PhpUnit
cd ../../

REM ---------------- 9.1.4 -------------------------

REM ECHO PHP 7.3.5 and PHPUnit 9.1.4 Simples
REM D:\wamp\bin\php\php7.3.5\php.exe D:\wamp\bin\phpunit\phpunit-9.1.4.phar --colors=auto --bootstrap D:\wamp\www\adianti\sysgenad\init.php D:\wamp\www\adianti\sysgenad\app\tests\

REM ECHO PHP 7.3.5 and PHPUnit 9.1.4 Simples with Coverage
REM D:\wamp\bin\php\php7.3.5\php.exe D:\wamp\bin\phpunit\phpunit-9.1.4.phar --colors=auto --bootstrap D:\wamp\www\adianti\sysgenad\init.php --whitelist D:\wamp\www\adianti\sysgenad\app\control\controllers --coverage-html D:\wamp\www\adianti\sysgenad\phpunit-code-coverage D:\wamp\www\adianti\sysgenad\app\tests\

REM ---------------- 9.5.9 -------------------------

ECHO PHP 8.1.0 and PHPUnit 9.5.9 Simples with Coverage
D:\wamp64\bin\php\php8.1.0\php.exe D:\wamp64\bin\phpunit\phpunit-9.5.9.phar --colors=auto --bootstrap D:\wamp64\www\adianti\sysgenad\init.php --whitelist D:\wamp64\www\adianti\formDin5\appexemplo_v1.0\app\lib\widget\FormDin5 --coverage-html D:\wamp64\www\adianti\sysgenad\phpunit-code-coverage D:\wamp64\www\adianti\sysgenad\app\tests\

cd app\tests\