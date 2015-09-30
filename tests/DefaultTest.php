<?php

class DefaultTest 
    extends PHPUnit_Framework_TestCase {
    
    const TEXT = '
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam et odio non lorem ullamcorper ullamcorper. Integer eu elementum risus. Etiam maximus lectus mollis bibendum suscipit. Nam pellentesque ipsum a lorem consequat laoreet. Proin convallis malesuada libero quis malesuada. Cras vestibulum quis urna suscipit interdum. Vestibulum nec molestie velit. Donec feugiat suscipit magna, et feugiat nunc suscipit ultrices. Ut sapien velit, molestie interdum vulputate quis, placerat ac ligula.

        Suspendisse eget turpis in nibh lobortis porta ut non lorem. Quisque pharetra, sapien nec pellentesque mattis, leo orci bibendum felis, sit amet ultricies eros urna ac elit. Morbi facilisis semper mauris ut lacinia. Aenean nec dolor non enim sodales pretium. Integer tempus lacinia laoreet. Vestibulum hendrerit tortor at diam ornare faucibus. Nulla interdum maximus ligula eu bibendum.

        Aenean condimentum erat vitae aliquam commodo. [b]Pellentesque[/b] congue, tortor in egestas dignissim, ipsum neque commodo felis, at efficitur felis nunc in quam. Fusce tempus leo ac convallis vulputate. Nunc vitae vehicula tortor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Pellentesque pulvinar eget dui sed rhoncus. Vivamus lobortis malesuada mi, vel scelerisque dolor posuere vel. Praesent at vulputate lacus. Sed a pulvinar purus. Integer pretium dui tortor, nec accumsan lorem egestas pharetra. Fusce molestie dui luctus urna imperdiet, sed blandit est dignissim. Donec in metus id quam venenatis ultricies eget eget mi. Nunc in pretium metus.

        Phasellus luctus posuere mauris, sed facilisis sapien luctus et. Nunc facilisis lectus id eros sodales, sed bibendum metus porttitor. Proin rutrum ac dolor sed aliquet. Integer bibendum ipsum sit amet congue congue. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Interdum et malesuada fames ac ante ipsum primis in faucibus. Quisque eget neque nec tortor pharetra eleifend. In ullamcorper metus id pellentesque aliquet.

        Nam faucibus urna massa, vel vestibulum mauris lacinia id. Phasellus vel turpis vitae turpis varius aliquet vel et est. Quisque sodales venenatis luctus. Aliquam a tortor quis ante consectetur pretium in eget lorem. Quisque malesuada turpis at felis elementum varius. Mauris et dui luctus, dignissim urna a, facilisis ipsum. Sed porttitor elementum magna at sollicitudin. Sed eget purus commodo, gravida risus id, aliquet nisi. Praesent interdum ac elit in laoreet.';
    
    const TITLE = 'Lorem ipsum';
    
    const SHORT_INFO = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam et odio non lorem ullamcorper ullamcorper.';
    
    public function testTagsGeneration() {
        $instance = \MekDrop\TagsGenerator\TagsGenerator::getInstance();
        $instance->importantWords = array(
            'Lorem',
            'Game',
            'TV',
            'Games.lt'
        );
        $tags = $instance->findTags(self::TITLE, self::SHORT_INFO, self::TEXT);
        $this->assertNotEmpty($tags, 'Returned tags was empty but can\'t be');
        $this->assertTrue(is_string($tags), "Returned result must be a string");
    }
    
}