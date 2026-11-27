<?php

test('Гість перенаправляється на сторінку входу', function (): void {
    $this->get('/')
        ->assertRedirectToRoute('login');
});
