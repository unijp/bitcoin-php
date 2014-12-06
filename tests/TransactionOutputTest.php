<?php

namespace Bitcoin\Tests;

use Bitcoin\TransactionOutput;
use Bitcoin\Script;

class TransactionOutputTest extends \PHPUnit_Framework_TestCase
{
    protected $out;

    public function __construct()
    {

    }

    public function setUp()
    {
        $this->out = new TransactionOutput();
    }

    public function testGetValueDefault()
    {
        $this->assertSame('0',$this->out->getValue());
    }

    public function testSetValue()
    {
        $this->out->setValue(1);
        $this->assertSame(1, $this->out->getValue());
    }

    public function testGetScriptBuf()
    {
        $this->assertNull($this->out->getScriptBuf());
    }

    public function testGetScript()
    {
        $script = $this->out->getScript();
        $this->assertInstanceOf('Bitcoin\Script', $script);
        $this->assertEmpty($script->serialize());
    }

    public function testSetScriptBuf()
    {
        $script = new Script();
        $script = $script->op('OP_2')->op('OP_3')->serialize();
        $buffer = new \Bitcoin\Util\Buffer($script);
        $this->out->setScriptBuf($buffer);

        $this->assertInstanceOf('Bitcoin\Util\Buffer', $this->out->getScriptBuf());
        $this->assertInstanceOf('Bitcoin\Script', $this->out->getScript());
        $this->assertSame('5253', $this->out->getScriptBuf()->serialize('hex'));
    }

    public function testFromParser()
    {
        $buffer = \Bitcoin\Util\Buffer::hex('cac10000000000001976a9140eff868646ece0af8bc979093585e80297112f1f88ac');
        $parser = new \Bitcoin\Util\Parser($buffer);
        $out = $this->out->fromParser($parser);
        $this->assertInstanceOf('Bitcoin\TransactionOutput', $out);
    }

    public function testSerialize()
    {
        $hex    = 'cac10000000000001976a9140eff868646ece0af8bc979093585e80297112f1f88ac';
        $buffer = \Bitcoin\Util\Buffer::hex($hex);
        $parser = new \Bitcoin\Util\Parser($buffer);
        $out    = $this->out->fromParser($parser);
        $this->assertSame($hex, $out->serialize('hex'));
    }

    public function testGetSize()
    {
        $hex    = 'cac10000000000001976a9140eff868646ece0af8bc979093585e80297112f1f88ac';
        $buffer = \Bitcoin\Util\Buffer::hex($hex);
        $parser = new \Bitcoin\Util\Parser($buffer);
        $out    = $this->out->fromParser($parser);
        $this->assertSame(34, $out->getSize());
        $this->assertSame(68, $out->getSize('hex'));

    }

    public function test__toString()
    {
        $hex    = 'cac10000000000001976a9140eff868646ece0af8bc979093585e80297112f1f88ac';
        $buffer = \Bitcoin\Util\Buffer::hex($hex);
        $parser = new \Bitcoin\Util\Parser($buffer);
        $out    = $this->out->fromParser($parser);
        $this->assertSame($hex, $out->__toString());

    }

    public function testToArray()
    {
        $hex    = 'cac10000000000001976a9140eff868646ece0af8bc979093585e80297112f1f88ac';
        $buffer = \Bitcoin\Util\Buffer::hex($hex);
        $parser = new \Bitcoin\Util\Parser($buffer);
        $out    = $this->out->fromParser($parser);
        $array  = $out->toArray();

        $this->assertSame('0.00049610', $array['value']);
        $this->assertSame('76a9140eff868646ece0af8bc979093585e80297112f1f88ac', $array['scriptPubKey']['hex']);
        $this->assertSame('OP_DUP OP_HASH160 0eff868646ece0af8bc979093585e80297112f1f OP_EQUALVERIFY OP_CHECKSIG', $array['scriptPubKey']['asm']);
    }
};