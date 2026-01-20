<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260126000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create track and playback_log tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE track (
            id SERIAL PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            artist VARCHAR(255) NOT NULL,
            price DECIMAL(10, 2) NOT NULL
        )');

        $this->addSql('CREATE TABLE playback_log (
            id SERIAL PRIMARY KEY,
            track_id INTEGER NOT NULL,
            played_at TIMESTAMP NOT NULL,
            amount_paid DECIMAL(10, 2) NOT NULL,
            CONSTRAINT fk_playback_log_track FOREIGN KEY (track_id) REFERENCES track(id) ON DELETE CASCADE
        )');

        $this->addSql('CREATE INDEX idx_playback_log_track ON playback_log(track_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS playback_log');
        $this->addSql('DROP TABLE IF EXISTS track');
    }
}
