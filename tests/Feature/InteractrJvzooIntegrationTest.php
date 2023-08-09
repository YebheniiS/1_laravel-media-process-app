<?php

namespace Tests\Feature;

use Tests\Utils\BaseIntegrationTest;

class InteractrJvzooIntegrationTest extends BaseIntegrationTest
{
    // Product ID's for testing
    const PID_INTERACTR = 347951;
    const PID_INTERACTR_PRO = 348075;
    const PID_INTERACTR_AGENCY = 348081;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_interactr_sale()
    {
        $this->assertTrue(
            $this->sale([
                'jvzoo',
                'SALE',
                self::PID_INTERACTR,
                'interactr'
            ])
        );
    }


    public function test_interactr_pro_sale()
    {
        $this->assertTrue(
            $this->sale([
                'jvzoo',
                'SALE',
                self::PID_INTERACTR_PRO,
                'interactr_pro'
            ])
        );
    }

    public function test_interactr_agency_sale()
    {
        $this->assertTrue(
            $this->sale([
                'jvzoo',
                'SALE',
                self::PID_INTERACTR_AGENCY,
                'interactr_agency'
            ])
        );
    }

    public function test_interactr_agency_refund()
    {
        $this->assertFalse(
            $this->sale([
                'jvzoo',
                'RFND',
                self::PID_INTERACTR_AGENCY,
                'interactr_agency'
            ])
        );
    }

    public function test_interactr_pro_refund()
    {
        $this->assertFalse(
            $this->sale([
                'jvzoo',
                'RFND',
                self::PID_INTERACTR_PRO,
                'interactr_pro'
            ])
        );
    }

    public function test_interactr_refund()
    {
        $this->assertFalse(
            $this->sale([
                'jvzoo',
                'RFND',
                self::PID_INTERACTR,
                'interactr'
            ])
        );
    }

    public function test_delete_user()
    {
        $this->assertNull(
            $this->deleteUser()
        );
    }
}
