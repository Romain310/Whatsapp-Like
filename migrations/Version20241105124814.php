<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241105124814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commission_temporaire ADD CONSTRAINT FK_40B34BDC73A201E5 FOREIGN KEY (createur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_40B34BDC73A201E5 ON commission_temporaire (createur_id)');
        $this->addSql('ALTER TABLE message CHANGE contenu contenu LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message CHANGE contenu contenu VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commission_temporaire DROP FOREIGN KEY FK_40B34BDC73A201E5');
        $this->addSql('DROP INDEX IDX_40B34BDC73A201E5 ON commission_temporaire');
    }
}
