<?php

use App\Models\User;
use OpenAI\Laravel\Facades\OpenAI;

test('we can get a PLS from an abstract', function () {
    // commented out because it's a slow test and actually hits the OpenAI API

    $user = User::factory()->create();

    $abstract = "Infectious salmon anaemia (ISA) is a viral disease that affects farmed Atlantic salmon (Salmo salar L.), often leading to mass mortalities. A quick detection of the ISA virus (ISAV) is crucial for decision-making and can prevent the occurrence of future outbreaks. Screening done by Canada's National Aquatic Animal Health Laboratory System (NAAHLS) uses quantitative reverse transcription PCR (RT-qPCR) followed by sequencing of PCR amplicons. As neither technique provides information regarding the infectivity of the virus, suspected virulent strains are subsequently tested using viral isolation. However, this stepwise process can require significant time to deliver results. To speed up this delivery, we have improved on these pre-existing techniques by combining the use of cell culture with RT-qPCR to detect replicative virus in as little as 5 days. Preliminary assays enabled the establishment of a minimal shift in Ct values over time, which is representative of viral replication in cultured cells. Subsequent blind panel analyses allowed the establishment of the optimal sampling days, as well as diagnostic sensitivity (DSe) and specificity (DSp) estimates. This method could be adopted not only by laboratories conducting diagnostic analyses for ISAV, but also for other slow-replicating viral agents that replicate through a budding mechanism.";

    $response = $this->actingAs($user)->post('api/utils/generate-pls', [
        'abstract' => $abstract,
    ]);

    ray($response->json());

    $response->assertStatus(200);
    expect($response->json('data'))->toHaveKey('pls');
})->skip(true, 'It actually hits the OpenAI API');
