<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240721102713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification_commission (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, commission_id INT NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_E717A47AA76ED395 (user_id), INDEX IDX_E717A47A202D1EB2 (commission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification_commission_temporaire (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, commission_temporaire_id INT NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_AD665F10A76ED395 (user_id), INDEX IDX_AD665F10A3377DDC (commission_temporaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification_commission ADD CONSTRAINT FK_E717A47AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification_commission ADD CONSTRAINT FK_E717A47A202D1EB2 FOREIGN KEY (commission_id) REFERENCES commission (id)');
        $this->addSql('ALTER TABLE notification_commission_temporaire ADD CONSTRAINT FK_AD665F10A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification_commission_temporaire ADD CONSTRAINT FK_AD665F10A3377DDC FOREIGN KEY (commission_temporaire_id) REFERENCES commission_temporaire (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification_commission DROP FOREIGN KEY FK_E717A47AA76ED395');
        $this->addSql('ALTER TABLE notification_commission DROP FOREIGN KEY FK_E717A47A202D1EB2');
        $this->addSql('ALTER TABLE notification_commission_temporaire DROP FOREIGN KEY FK_AD665F10A76ED395');
        $this->addSql('ALTER TABLE notification_commission_temporaire DROP FOREIGN KEY FK_AD665F10A3377DDC');
        $this->addSql('DROP TABLE notification_commission');
        $this->addSql('DROP TABLE notification_commission_temporaire');
    }
}
