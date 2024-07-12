<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240712125544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, contenu VARCHAR(255) NOT NULL, date_envoi DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_commission (message_id INT NOT NULL, commission_id INT NOT NULL, INDEX IDX_D3B52DC7537A1329 (message_id), INDEX IDX_D3B52DC7202D1EB2 (commission_id), PRIMARY KEY(message_id, commission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_commission_temporaire (message_id INT NOT NULL, commission_temporaire_id INT NOT NULL, INDEX IDX_E8BCB9B2537A1329 (message_id), INDEX IDX_E8BCB9B2A3377DDC (commission_temporaire_id), PRIMARY KEY(message_id, commission_temporaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message_commission ADD CONSTRAINT FK_D3B52DC7537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message_commission ADD CONSTRAINT FK_D3B52DC7202D1EB2 FOREIGN KEY (commission_id) REFERENCES commission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message_commission_temporaire ADD CONSTRAINT FK_E8BCB9B2537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message_commission_temporaire ADD CONSTRAINT FK_E8BCB9B2A3377DDC FOREIGN KEY (commission_temporaire_id) REFERENCES commission_temporaire (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message_commission DROP FOREIGN KEY FK_D3B52DC7537A1329');
        $this->addSql('ALTER TABLE message_commission DROP FOREIGN KEY FK_D3B52DC7202D1EB2');
        $this->addSql('ALTER TABLE message_commission_temporaire DROP FOREIGN KEY FK_E8BCB9B2537A1329');
        $this->addSql('ALTER TABLE message_commission_temporaire DROP FOREIGN KEY FK_E8BCB9B2A3377DDC');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE message_commission');
        $this->addSql('DROP TABLE message_commission_temporaire');
    }
}
