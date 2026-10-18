<?php

namespace Tests\Unit;

use App\Models\Product;
use PHPUnit\Framework\TestCase;

/**
 * Las vistas preguntan `v-if="product.discount"`. Con el cast decimal:2 el valor llegaba como
 * el texto "0.00" (verdadero en JS) y un producto sin descuento mostraba «-0.00%».
 */
class ProductDiscountCastTest extends TestCase
{
    public function test_sin_descuento_llega_como_cero_numerico(): void
    {
        $p = new Product(['discount' => '0.00']);

        $this->assertSame(0.0, $p->toArray()['discount']);
    }

    public function test_con_descuento_conserva_el_valor(): void
    {
        $p = new Product(['discount' => '15.50']);

        $this->assertSame(15.5, $p->toArray()['discount']);
    }
}
