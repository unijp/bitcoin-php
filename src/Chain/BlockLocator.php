<?php

namespace BitWasp\Bitcoin\Chain;

use BitWasp\Bitcoin\Serializable;
use BitWasp\Bitcoin\Serializer\Chain\BlockLocatorSerializer;
use BitWasp\Buffertools\BufferInterface;

class BlockLocator extends Serializable
{
    /**
     * @var BufferInterface[]
     */
    private $hashes;

    /**
     * @var BufferInterface
     */
    private $hashStop;

    /**
     * @param BufferInterface[] $hashes
     * @param BufferInterface $hashStop
     */
    public function __construct(array $hashes, BufferInterface $hashStop)
    {
        foreach ($hashes as $hash) {
            $this->addHash($hash);
        }

        $this->hashStop = $hashStop;
    }

    /**
     * @param BufferInterface $hash
     */
    private function addHash(BufferInterface $hash)
    {
        $this->hashes[] = $hash;
    }

    /**
     * @return BufferInterface[]
     */
    public function getHashes()
    {
        return $this->hashes;
    }

    /**
     * @return BufferInterface
     */
    public function getHashStop()
    {
        return $this->hashStop;
    }

    /**
     * @return BufferInterface
     */
    public function getBuffer()
    {
        return (new BlockLocatorSerializer())->serialize($this);
    }
}
