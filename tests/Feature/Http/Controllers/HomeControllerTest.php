<?php

use App\Models\UpcomingChange;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('has a link to the current catalogue on the landing page', function () {
    $this->signIn();

    $response = $this->get(route('home'));

    $response->assertSee('<a href="https://issuu.com/puritylifehealthproducts/docs/julaugsep_purity_pulse.flip?fr=xPf9vPhtCa0M7li9TlywCbgpiITsGDwP0wf46CP46wQrB_lNYNsH_CEVVTEdXOFA08dcD_k8G_lYC_lUS8f8DX0c4Jv8CWUIED_8FazVQOF8s_wVLMUpaORL_AllRBjv-QQP_AlE_Av8EPUtENgP_AjcxSjVDQEBAbjuWL1OXLAL0wUA7yHVQYcEmOxYG4QrB_wQyMDIxwf8CMTLBJQomJv8CQktu_mo7OlAB" target="_blank">', false);
});

it('has the correct catalogue image on the landing page', function () {
    $this->signIn();

    $response = $this->get(route('home'));

    $response->assertSee('July2023.png');
});

it('has a link to the current marketing opportunities booklet on the landing page', function () {
    $this->signIn();

    $response = $this->get(route('home'));

    $response->assertSee('<a href="https://issuu.com/puritylifehealthproducts/docs/pl_marketingopportunities2023forissuu?fr=sMTViMjUzOTA0NTU" target="_blank">', false);
});

it('shows the upcoming changes header', function () {
    $this->signIn();

    $this->get(route('home'))->assertSee('Feature Updates to PLIC');
});

it('displays upcoming changes', function () {
    $this->signIn();

    $upcomingChange = UpcomingChange::create([
        'title' => 'Test Change',
        'description' => 'Test Description',
        'change_date' => '2022-06-01',
        'expires_at' => now()->addMonth(1),
        'scheduled_at' => '2022-05-01',
    ]);

    $response = $this
        ->get(route('home'))
        ->assertViewHas(
            'upcomingChanges',
            fn ($upcomingChanges) => $upcomingChanges->contains($upcomingChange)
        );
});

it('does not display expired upcoming changes', function () {
    $this->signIn();

    $this->travelTo(CarbonImmutable::parse('2022-05-01'));

    $notExpiredChange = UpcomingChange::create([
        'title' => 'Test Change',
        'description' => 'Test Description',
        'change_date' => '2022-06-01',
        'expires_at' => '2022-06-30',
        'scheduled_at' => '2022-05-01',
    ]);

    $expiredChange = UpcomingChange::create([
        'title' => 'Test Change',
        'description' => 'Test Description',
        'change_date' => '2022-06-01',
        'expires_at' => '2022-05-01',
        'scheduled_at' => '2022-04-01',
    ]);

    $response = $this->get(route('home'));

    $response->assertViewHas('upcomingChanges', fn ($upcomingChanges) => $upcomingChanges->contains($notExpiredChange));
    $response->assertViewHas('upcomingChanges', fn ($upcomingChanges) => $upcomingChanges->doesntContain($expiredChange));
});

it('does not display upcoming changes scheduled in the future', function () {
    $this->signIn();

    $this->travelTo(CarbonImmutable::parse('2022-05-01'));

    $activeChange = UpcomingChange::create([
        'title' => 'Test Change',
        'description' => 'Test Description',
        'change_date' => '2022-06-01',
        'expires_at' => '2022-06-30',
        'scheduled_at' => '2022-05-01',
    ]);

    $scheduledChange = UpcomingChange::create([
        'title' => 'Test Change',
        'description' => 'Test Description',
        'change_date' => '2022-06-01',
        'expires_at' => '2022-07-01',
        'scheduled_at' => '2022-06-01',
    ]);

    $response = $this->get(route('home'));

    $response->assertViewHas('upcomingChanges', fn ($upcomingChanges) => $upcomingChanges->contains($activeChange));
    $response->assertViewHas('upcomingChanges', fn ($upcomingChanges) => $upcomingChanges->doesntContain($scheduledChange));
});

it('orders upcoming changes by closest change date', function () {
    $this->signIn();

    $this->travelTo(CarbonImmutable::parse('2022-04-01'));

    $lastChange = UpcomingChange::create([
        'title' => 'Last Change',
        'description' => 'Last description',
        'change_date' => '2022-07-01',
        'expires_at' => '2022-12-01',
        'scheduled_at' => '2022-03-01',
    ]);

    $firstChange = UpcomingChange::create([
        'title' => 'First Change',
        'description' => 'First description',
        'change_date' => '2022-05-01',
        'expires_at' => '2022-12-01',
        'scheduled_at' => '2022-03-01',
    ]);

    $middleChange = UpcomingChange::create([
        'title' => 'Middle Change',
        'description' => 'Middle description',
        'change_date' => '2022-06-01',
        'expires_at' => '2022-12-01',
        'scheduled_at' => '2022-03-01',
    ]);

    $response = $this->get(route('home'));

    $response->assertSeeInOrder([
        'First Change',
        'Middle Change',
        'Last Change',
    ]);
});
