<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260126000002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert sample tracks for testing';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO track (title, artist, price) VALUES 
            ('Bohemian Rhapsody', 'Queen', '2.50'),
            ('Hotel California', 'Eagles', '2.00'),
            ('Sweet Home Alabama', 'Lynyrd Skynyrd', '1.75'),
            ('Stairway to Heaven', 'Led Zeppelin', '3.00'),
            ('Back in Black', 'AC/DC', '2.25')
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM track');
    }
}
