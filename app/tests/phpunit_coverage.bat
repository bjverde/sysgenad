@echo off 

ECHO Teste PhpUnit
cd ../../

REM ---------------- 9.1.4 -------------------------

REM ECHO PHP 7.4.33 and PHPUnit 9.1.4 Simples
REM C:\wamp64\bin\php\php7.4.33\php.exe C:\wamp64\bin\phpunit\phpunit-9.5.9.phar --colors=auto --bootstrap C:\wamp64\www\adinatiApp\sysgenad\init.php C:\wamp64\www\adinatiApp\sysgenad\app\tests\

REM ECHO PHP 7.4.33 and PHPUnit 9.5.9 Simples with Coverage
REM C:\wamp64\bin\php\php7.4.33\php.exe C:\wamp64\bin\phpunit\phpunit-9.5.9.phar --colors=auto --bootstrap C:\wamp64\www\adinatiApp\sysgenad\init.php --whitelist C:\wamp64\www\adiantiApp\sysgenad\app\control\controllers --coverage-html C:\wamp64\www\adinatiApp\sysgenad\phpunit-code-coverage C:\wamp64\www\adinatiApp\sysgenad\app\tests\

REM ECHO PHP 8.1.13 and PHPUnit 9.5.9 Simples with Coverage
REM C:\wamp64\bin\php\php8.1.13\php.exe C:\wamp64\bin\phpunit\phpunit-9.5.9.phar --colors=auto --bootstrap C:\wamp64\www\adinatiApp\sysgenad\init.php --whitelist C:\wamp64\www\adiantiApp\sysgenad\app\control\controllers --coverage-html C:\wamp64\www\adiantiApp\sysgenad\phpunit-code-coverage C:\wamp64\www\adinatiApp\sysgenad\app\tests\

ECHO PHP 8.2.6 and PHPUnit 10.1.3 Simples
C:\wamp64\bin\php\php8.2.6\php.exe C:\wamp64\bin\phpunit\phpunit-10.1.3.phar --colors=auto --bootstrap C:\wamp64\www\adinatiApp\sysgenad\init.php C:\wamp64\www\adinatiApp\sysgenad\app\tests\

REM ECHO PHP 8.2.6 and PHPUnit 10.1.3 Simples with Coverage
REM C:\wamp64\bin\php\php8.2.6\php.exe C:\wamp64\bin\phpunit\phpunit-10.1.3.phar --colors=auto --bootstrap C:\wamp64\www\adinatiApp\sysgenad\init.php --whitelist C:\wamp64\www\adiantiApp\sysgenad\app\control\controllers --coverage-html C:\wamp64\www\adiantiApp\sysgenad\phpunit-code-coverage C:\wamp64\www\adinatiApp\sysgenad\app\tests\

cd app\tests\