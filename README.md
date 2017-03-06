# spell-checker

This is a PHP script that will check for spelling errors in a text file, given a dictionary of valid words.
It can be executed via the command line. (shown below)

```bash
php spellchecker.php dictionary.txt badSpelling.txt
```
Any words that are not found in the dictionary, will be output along with their line and column numbers.
```bash
Misspelled words:
1:9	    speled
1:16	gud
1:24	teh
1:28	peopl
```

## The Features

* Outputs a list of incorrectly spelled words.
* Includes the line and column number of the misspelled word

## Known Limitations

* Does not offer suggestions for a correctly spelled word.
* Does not handle words with internal punctuation. For example contractions. (isn't, I'm, hasn't)
* Does not handle proper nouns.
