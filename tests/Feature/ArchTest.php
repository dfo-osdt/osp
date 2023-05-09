<?php

test('global')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();
