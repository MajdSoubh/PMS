<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Drug>
 */
class DrugFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private $drugNames = [
        "Lipitor",
        "Zocor",
        "Synthroid",
        "Nexium",
        "Plavix",
        "Advair Diskus",
        "Abilify",
        "Seroquel",
        "Singulair",
        "Crestor",
        "Actos",
        "Cymbalta",
        "Humira",
        "Enbrel",
        "Remicade",
        "Januvia",
        "Lantus Solostar",
        "Vyvanse",
        "Lyrica",
        "Spiriva Handihaler",
        "Suboxone",
        "Symbicort",
        "Truvada",
        "Xarelto",
        "Eliquis",
        "Harvoni",
        "Sovaldi",
        "Gleevec",
        "Tamiflu",
        "Herceptin"
    ];
    private $drugDescriptions = [
        "Lipitor is used to lower cholesterol levels and reduce the risk of heart attack, stroke, and other heart complications.",
        "Zocor is used to lower cholesterol levels and reduce the risk of heart attack, stroke, and other heart complications.",
        "Synthroid is used to treat an underactive thyroid gland (hypothyroidism).",
        "Nexium is used to treat gastroesophageal reflux disease (GERD) and other conditions involving excessive stomach acid production.",
        "Plavix is used to reduce the risk of heart attack, stroke, and other heart complications by preventing blood clots.",
        "Advair Diskus is used to treat asthma and chronic obstructive pulmonary disease (COPD) by reducing inflammation and opening up airways.",
        "Abilify is used to treat schizophrenia, bipolar disorder, and depression by altering the activity of certain chemicals in the brain.",
        "Seroquel is used to treat schizophrenia, bipolar disorder, and depression by altering the activity of certain chemicals in the brain.",
        "Singulair is used to treat asthma and allergies by reducing inflammation and opening up airways.",
        "Crestor is used to lower cholesterol levels and reduce the risk of heart attack, stroke, and other heart complications.",
        "Actos is used to treat type 2 diabetes by improving insulin sensitivity and reducing blood sugar levels.",
        "Cymbalta is used to treat depression, anxiety, and chronic pain by altering the activity of certain chemicals in the brain.",
        "Humira is used to treat autoimmune diseases such as rheumatoid arthritis, psoriasis, and Crohn's disease by reducing inflammation.",
        "Enbrel is used to treat autoimmune diseases such as rheumatoid arthritis and psoriasis by reducing inflammation.",
        "Remicade is used to treat autoimmune diseases such as rheumatoid arthritis, psoriasis, and Crohn's disease by reducing inflammation.",
        "Januvia is used to treat type 2 diabetes by improving insulin sensitivity and reducing blood sugar levels.",
        "Lantus Solostar is a long-acting insulin used to treat type 1 and type 2 diabetes by regulating blood sugar levels.",
        "Vyvanse is used to treat attention deficit hyperactivity disorder (ADHD) and binge eating disorder by altering the activity of certain chemicals in the brain.",
        "Lyrica is used to treat epilepsy, fibromyalgia, and nerve pain by altering the activity of certain chemicals in the brain.",
        "Spiriva Handihaler is used to treat COPD and asthma by reducing inflammation and opening up airways.",
        "Suboxone is used to treat opioid addiction by reducing withdrawal symptoms and cravings.",
        "Symbicort is used to treat asthma and COPD by reducing inflammation and opening up airways.",
        "Truvada is used to treat and prevent HIV infection by blocking the virus from multiplying in the body.",
        "Xarelto is used to prevent blood clots and reduce the risk of stroke in people with atrial fibrillation.",
        "Eliquis is used to prevent blood clots and reduce the risk of stroke in people with atrial fibrillation.",
        "Harvoni is used to treat hepatitis C by blocking the virus from multiplying in the body.",
        "Sovaldi is used to treat hepatitis C by blocking the virus from multiplying in the body.",
        "Gleevec is used to treat certain types of leukemia and other cancers by blocking the activity of certain proteins that promote cancer growth.",
        "Tamiflu is used to treat and prevent influenza (the flu) by blocking the activity of the virus.",
        "Herceptin is used to treat certain types of breast cancer by blocking the activity of a protein that promotes cancer growth."
    ];
    public function definition()
    {

        $selectedDrugInd = fake()->unique()->numberBetween(0, count($this->drugNames) - 1);
        return [
            'name' => $this->drugNames[$selectedDrugInd],
            'generic' => $this->drugNames[$selectedDrugInd],
            'description' => $this->drugDescriptions[$selectedDrugInd],
            'price' => fake()->numberBetween(1000, 6000),


        ];
    }
}
