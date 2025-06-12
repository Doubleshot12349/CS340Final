--NumSpells.sql
--Created by Group 5: Connor Sun and Brett Thompson
CREATE TRIGGER `NumSpells` AFTER INSERT ON `contains`
 FOR EACH ROW BEGIN
UPDATE Spellbook
SET number_spells = 1 + number_spells
 WHERE Spellbook.spellbook_id=NEW.spellbook_id;
END