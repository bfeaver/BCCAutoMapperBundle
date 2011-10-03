<?php

namespace BCC\AutoMapperBundle\Tests\Mapper;

use BCC\AutoMapperBundle\Tests\Fixtures\DestinationPost;
use BCC\AutoMapperBundle\Tests\Fixtures\SourcePost;
use BCC\AutoMapperBundle\Tests\Fixtures\SourceAuthor;
use BCC\AutoMapperBundle\Mapper\Mapper;
use BCC\AutoMapperBundle\Mapper\FieldAccessor\ClosureFieldAccessor;
use BCC\AutoMapperBundle\Tests\Fixtures\PostMap;

/**
 * @author Michel Salib <michelsalib@hotmail.com>
 */
class MapperTest extends \PHPUnit_Framework_TestCase {

    public function testDefaultMap() {
        // ARRANGE
        $source = new SourcePost();
        $source->description = 'Symfony2 developer';
        $destination = new DestinationPost();
        $mapper = new Mapper();
        $mapper->createMap('BCC\AutoMapperBundle\Tests\Fixtures\SourcePost', 'BCC\AutoMapperBundle\Tests\Fixtures\DestinationPost');

        // ACT
        $mapper->map($source, $destination);

        // ASSERT
        $this->assertEquals('Symfony2 developer', $destination->description);
    }

    public function testCustomMap() {
        // ARRANGE
        $source = new SourcePost();
        $source->name = 'Michel';
        $source->description = 'Symfony2 developer';
        $destination = new DestinationPost();
        $mapper = new Mapper();
        $mapper->createMap('BCC\AutoMapperBundle\Tests\Fixtures\SourcePost', 'BCC\AutoMapperBundle\Tests\Fixtures\DestinationPost')
               ->route('title', 'name');

        // ACT
        $mapper->map($source, $destination);

        // ASSERT
        $this->assertEquals('Michel', $destination->title);
        $this->assertEquals('Symfony2 developer', $destination->description);
    }
    
    public function testInDepthMap() {
        // ARRANGE
        $source = new SourcePost();
        $source->author = new SourceAuthor();
        $source->author->name = 'Michel';
        $destination = new DestinationPost();
        $mapper = new Mapper();
        $mapper->createMap('BCC\AutoMapperBundle\Tests\Fixtures\SourcePost', 'BCC\AutoMapperBundle\Tests\Fixtures\DestinationPost')
               ->route('author', 'author.name');

        // ACT
        $mapper->map($source, $destination);

        // ASSERT
        $this->assertEquals('Michel', $destination->author);
    }
    
    public function testClosuredMap() {
        // ARRANGE
        $source = new SourcePost();
        $source->author = new SourceAuthor();
        $source->author->name = 'Michel';
        $destination = new DestinationPost();
        $mapper = new Mapper();
        $mapper->createMap('BCC\AutoMapperBundle\Tests\Fixtures\SourcePost', 'BCC\AutoMapperBundle\Tests\Fixtures\DestinationPost')
               ->forMember('author', new ClosureFieldAccessor(function(SourcePost $s){
                   return $s->author->name;
               }));

        // ACT
        $mapper->map($source, $destination);

        // ASSERT
        $this->assertEquals('Michel', $destination->author);
    }
    
    public function testMapRegistring(){
        // ARRANGE
        $source = new SourcePost();
        $source->name = 'Michel';
        $source->description = 'Symfony2 developer';
        $destination = new DestinationPost();
        $mapper = new Mapper();
        $mapper->registerMap(new PostMap());

        // ACT
        $mapper->map($source, $destination);

        // ASSERT
        $this->assertEquals('Michel', $destination->title);
        $this->assertEquals('Symfony2 developer', $destination->description);
    }
}
