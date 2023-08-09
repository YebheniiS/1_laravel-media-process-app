<?php

namespace Tests\Feature;

use Tests\Utils\BaseIntegrationTest;

class InteractrPaykickstartIntegrationTest extends BaseIntegrationTest
{
    const PID_INTERACTR_BASIC = 11;
    const PID_INTERACTR_PRO = 22;
    const PID_INTERACTR_PRO_FB = 55;
    const PID_INTERACTR_AGENCY = 44;

    public function test_interactr_basic()
    {
        $this->sale([
            'pks',
            'sales',
            self::PID_INTERACTR_BASIC
        ]);
        $this->cancel([
            'pks',
            'subscription-cancelled',
            self::PID_INTERACTR_BASIC,
        ]);
        $this->assertNull(
            $this->deleteUser()
        );
    }

    public function test_interactr_pro_fb()
    {
        $this->sale([
            'pks',
            'sales',
            self::PID_INTERACTR_BASIC
        ]);
        $this->sale([
            'pks',
            'sales',
            self::PID_INTERACTR_PRO_FB
        ]);
        $this->cancel([
            'pks',
            'subscription-cancelled',
            self::PID_INTERACTR_PRO_FB,
        ]);
        $this->assertNull(
            $this->deleteUser()
        );
    }

    public function test_agency()
    {
        $this->sale([
            'pks',
            'sales',
            self::PID_INTERACTR_BASIC
        ]);
        $this->sale([
            'pks',
            'sales',
            self::PID_INTERACTR_AGENCY
        ]);
        $this->cancel([
            'pks',
            'subscription-cancelled',
            self::PID_INTERACTR_AGENCY,
        ]);
        $this->assertNull(
            $this->deleteUser()
        );
    }
}
