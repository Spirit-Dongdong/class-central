<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120119010131 extends AbstractMigration {

    public function up(Schema $schema) {
        // Create table universities
        $this->addSql("CREATE  TABLE IF NOT EXISTS universities (
                        id INT NOT NULL AUTO_INCREMENT ,
                        name VARCHAR(255) NOT NULL , 
                        url  TEXT NULL,
                         PRIMARY KEY (id) )
                         ENGINE = InnoDB;");

        // Create table initiatives
        $this->addSql("CREATE  TABLE IF NOT EXISTS initiatives (
                       id INT NOT NULL AUTO_INCREMENT ,
                       name VARCHAR(50) NOT NULL ,
                       url  TEXT NULL ,
                       description TEXT NULL ,
                       tooltip VARCHAR(255) NULL,
                       code VARCHAR(20) NOT NULL ,
                        PRIMARY KEY (id) )
                        ENGINE = InnoDB;");
        
        // Add column university_id to instructors table
        $this->addSql("ALTER TABLE instructors ADD COLUMN university_id INT NULL; 
                        ALTER TABLE instructors
                        ADD CONSTRAINT university_id
                        FOREIGN KEY (university_id)
                        REFERENCES universities (id )
                        ON DELETE NO ACTION
                        ON UPDATE NO ACTION");
        
        // Add column initiative_id, name to offerings table
        $this->addSql("ALTER TABLE offerings ADD COLUMN initiative_id INT NULL;
                        ALTER TABLE offerings ADD COLUMN name VARCHAR(255) NULL;
                        ALTER TABLE offerings
                        ADD CONSTRAINT initiative_id
                        FOREIGN KEY (initiative_id )
                        REFERENCES initiatives (id )
                        ON DELETE NO ACTION
                        ON UPDATE NO ACTION");
        
        // Add data to intiatives table
        $this->addSql("INSERT INTO initiatives(name,url,code,tooltip) 
                       VALUES('Coursera', 'http://www.coursera.org','COURSERA','Coursera by Stanford University'),
                             ('MITx', 'http://mitx.mit.edu/', 'MITX','MITx by MIT'),
                             ('Udacity','http://www.udacity.com/','UDACITY','Udacity by Know Labs')");
        
        // Set initiative ids for exisiting courses
        $this->addSql("UPDATE offerings SET initiative_id = (SELECT id FROM initiatives WHERE code='COURSERA') WHERE url != 'https://www.ai-class.com/'");
        $this->addSql("UPDATE offerings SET initiative_id = (SELECT id FROM initiatives WHERE code='UDACITY') WHERE url = 'https://www.ai-class.com/'");
        
        
    }

    public function down(Schema $schema) {
        
        
        // Drop column initiative_id from offerings table
        $this->addSql("ALTER TABLE offerings DROP FOREIGN KEY initiative_id;
                        ALTER TABLE offerings DROP COLUMN initiative_id ;
                        ALTER TABLE offerings DROP COLUMN name");
                       
        
        // Drop column  university_id from instructors table
        $this->addSql("ALTER TABLE instructors DROP FOREIGN KEY university_id;
                        ALTER TABLE instructors DROP COLUMN university_id ");
        // Drop table universities and initiatives
        $this->addSql("DROP TABLE IF EXISTS universities, initiatives");
    }

}
