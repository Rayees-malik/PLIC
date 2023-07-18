<?php

namespace Deployer;

desc('Execute artisan as400:migrate');
task('artisan:as400:migrate', artisan('as400:migrate', ['showOutput']));
