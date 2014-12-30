<?php
namespace inklabs\kommerce\Entity;

use Symfony\Component\Validator\Validation;

class AddressTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $address = new Address;
        $address->setAttention('John Doe');
        $address->setCompany('Acme Co.');
        $address->setAddress1('123 Any St');
        $address->setAddress2('Ste 3');
        $address->setCity('Santa Monica');
        $address->setState('CA');
        $address->setZip5('90401');
        $address->setZip4('3274');
        $address->setLatitude(34.010947);
        $address->setLongitude(-118.490541);

        $validator = Validation::createValidatorBuilder()
            ->addMethodMapping('loadValidatorMetadata')
            ->getValidator();

        $this->assertEmpty($validator->validate($address));
        $this->assertSame('John Doe', $address->getAttention());
        $this->assertSame('Acme Co.', $address->getCompany());
        $this->assertSame('123 Any St', $address->getAddress1());
        $this->assertSame('Ste 3', $address->getAddress2());
        $this->assertSame('Santa Monica', $address->getCity());
        $this->assertSame('CA', $address->getState());
        $this->assertSame('90401', $address->getZip5());
        $this->assertSame('3274', $address->getZip4());
        $this->assertEquals(34.010947, $address->getLatitude(), '', 0.000001);
        $this->assertEquals(-118.490541, $address->getLongitude(), '', 0.000001);
        $this->assertTrue($address->getView() instanceof View\Address);
    }
}
