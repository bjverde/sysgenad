@echo off 

ECHO Teste PhpUnit
cd ../../

REM ---------------- 9.1.4 -------------------------

REM ECHO PHP 7.3.5 and PHPUnit 9.1.4 Simples
REM D:\wamp\bin\php\php7.3.5\php.exe D:\wamp\bin\phpunit\phpunit-9.1.4.phar --colors=auto --bootstrap D:\wamp\www\adianti\sysgenad\init.php D:\wamp\www\adianti\sysgenad\app\tests\

ECHO PHP 7.3.5 and PHPUnit 9.1.4 Simples with Coverage
D:\wamp\bin\php\php7.3.5\php.exe D:\wamp\bin\phpunit\phpunit-9.1.4.phar --colors=auto --bootstrap D:\wamp\www\adianti\sysgenad\init.php --whitelist D:\wamp\www\adianti\sysgenad\app\control\controllers --coverage-html D:\wamp\www\adianti\sysgenad\phpunit-code-coverage D:\wamp\www\adianti\sysgenad\app\tests\


cd app\tests\