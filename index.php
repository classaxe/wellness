<?php
/* Create a class hierarchy to represent files and directories.
Directories can contain files and other directories.
For both files and directories their names and creation dates are known.
We would like the classes, any properties, dependencies between classes but no methods, not even getters/setters or constructors.
The code you will be creating does not need to be run.

Add a method to find the most popular name between files and
directories, including all subdirectories
*/

class FileElement {
    protected $id;
    protected $name;
    protected $dateStamp;
    protected $mimeType;
    protected $contents;

    public function __construct(
        string $name,
        mixed $contents,
        string $mimeType = null
    ) {
        $this->dateStamp = time();
        $this->id = getGUID();
        $this->name = $name;
        $this->contents = $contents;
        $this->mimeType = $mimeType;
    }

    /**
     * Getter for contents property;
     * @return mixed
     */
    public function getContents() {
        return $this->contents;
    }

    /**
     * Getter for name property;
     * @return string
     */
    public function getName() {
        return $this->name;
    }

     /**
     * Is this element a direcrory?
     * @return bool
     */
    public function isDir() {
        return false;
    }
}

class Dir extends FileElement {

     /**
     * Is this element a direcrory?
     * @return bool
     */
    public function isDir() {
        return true;
    }

    /*
     * Helper Function for cataloging file names
     * @param $namesArray
     * @return mixed
    */
    public function findAllNames($namesArray = []) {
        foreach ($this->getContents() as $item) {
            if ($item->isDir()) {    
                $namesArray = $item->findAllNames($namesArray);
            }
            $name = $item->getName();
            if (!isset($namesArray[$name])) {
                $namesArray[$name] = 0;
            }
            $namesArray[$name] ++;
        }
        return $namesArray;
    }

    public function getPopular() {
        $names = $this->findAllNames();
        arsort($names);
        return array_key_first($names);
    }

    public function getPopularCount() {
        $names = $this->findAllNames();
        arsort($names);
        return $names[array_key_first($names)];
    }

    public function __toString()
    {
        return "<pre>" . var_export($this, true) . "</pre>";
    }
}

class File extends FileElement {
     /**
     * Is this element a direcrory?
     * @return bool
     */
    public function isDir() {
        return false;
    }
}

/**
 * Function to generate Microsoft-style GUID for entry indexing
 * @return string
 */
function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }
    mt_srand((int) (double)microtime()*10000);
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $hyphen = chr(45);// "-"
    $uuid = chr(123)// "{"
        .substr($charid, 0, 8).$hyphen
        .substr($charid, 8, 4).$hyphen
        .substr($charid,12, 4).$hyphen
        .substr($charid,16, 4).$hyphen
        .substr($charid,20,12)
        .chr(125);// "}"
    return $uuid;
}

$root = new Dir(
    '/',
    [
        new File(
            'Readme.txt',
            'Hello World!',
            'text/plain'
        ),
        new File(
            'favicon.ico',
            'data:image/x-icon;base64,AAABAAEAEBAQAAEABAAoAQAAFgAAACgAAAAQAAAAIAAAAAEABAAAAAAAgAAAAAAAAAAAAAAAEAAAAAAAAABivMcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAERERERERERERERAAAAERERERAREREBERERARERERAREREBEREREBEREBERERERAREQEREREREBEQERERERERARAREREREREBEQEREREREBERAREREREQEREQEREREQERERARERERAREREQERERAREREREAAAARERERERERERERH//wAA+B8AAPfvAADv9wAA7/cAAN/7AADf+wAAv/0AAL/9AADf+wAA3/sAAO/3AADv9wAA9+8AAPgfAAD//wAA',
            'image/icon'
        ),
        new File(
            '.gitignore',
            '.idea\n',
            'text/plain'
        ),
        new Dir(
            'assets',
            [
                new File(
                    '.gitignore',
                    '.idea\n',
                    'text/plain'
                ),
                new File(
                    'Readme.txt',
                    'Empty Folder for now',
                    'text/plain'
                )       
            ]
        ),
        new Dir(
            'source',
            [
                new Dir(
                    'Controllers',
                    []
                ),
            ]
        ),
        new Dir(
            'vendors',
            [
                new File(
                    '.gitignore',
                    '*'
                )
            ]
        )
    ]
);
?>
<html>
<head>
    <title>Wellness Living Live Coding Challenge</title>
</head>
<body>
<h1>Wellness Living Live Coding Challenge</h1>
<p>Most popular filesname:</p>
<strong><?php print $root->getPopular(); ?> (<?php print $root->getPopularCount(); ?> instances)</strong>
<p>Root folder contents:</p>
<?php print $root; ?>
</body>
</html>