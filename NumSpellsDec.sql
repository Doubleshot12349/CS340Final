--NumSpellsDec.sql
--Created by Group 5: Connor Sun and Brett Thompson
CREATE TRIGGER `NumSpellsDec` AFTER DELETE ON `contains`
 FOR EACH ROW BEGIN
UPDATE Spellbook
SET number_spells = number_spells-1
 WHERE Spellbook.spellbook_id=OLD.spellbook_id;
END