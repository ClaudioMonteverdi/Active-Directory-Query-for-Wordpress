<?php
$markup = generate_markup(map_data());

echo $markup;

function get_data()
{
        // LDAP variables
        $ldaphost = "ldap://.local";      // your ldap servers
        $ldapport = 389;                 // your ldap server's port number
        $username = “DOMAIN\\USER”;     //username to connect to AD (use read only)
        $password = ‘PASSWORD’;        //Password 
        $basedn = "ou=,dc=,dc=";      //asssign your base DC


        // Connecting to LDAP
        $ldapconn = ldap_connect($ldaphost);

        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

        if($ldapconn)
        {
                $bind = ldap_bind($ldapconn, $username, $password);

                if($bind)
                {
                        $results = ldap_search($ldapconn, $basedn, "(memberOf=CN=GROUP,OU=OU,DC=DC,DC=local)"); //members of this group will be queried
                        $info = ldap_get_entries($ldapconn, $results);
                        return $info;
                }

                return array();
        }
}

function map_data()
{
        $data = get_data();

        // the first result is always an integer of the total number of records
        // we want to remove this item so it is not parsed into a person object

        unset($data['count']);

        // generate a new person collection with the data
        return new PersonCollection($data);
}

function generate_markup(PersonCollection $collection)
{
        $markup = '<table class="table" cellspacing="0" style="margin-top: 0px">' . "\n";
        $markup .=      '<tbody>' . "\n";
        $markup .=      '<tr>' . "\n";
        $markup .=              '<th>Name</th>' . "\n";
        $markup .=              '<th>Title</th>' . "\n";
        $markup .=              '<th>Extension</th>' . "\n";
        $markup .=              '<th>Email</th>' . "\n";
        $markup .=      '</tr>' . "\n";


        foreach($collection->people as $key => $person)
        {

                // if it's even
                $markup .= '<tr ' . ($key % 2 == 0 ? 'class="even"' : '') . ">\n";

                $markup .= "<td>" . $person->getName() . "</td>" . "\n";
                $markup .= "<td>{$person->title}</td>" . "\n";
                $markup .= "<td>{$person->phone}</td>" . "\n";
                $markup .= "<td>{$person->email}</td>" . "\n";

                $markup .= '</tr>' . "\n";
        }

        $markup .=      '</tbody>' . "\n";
        $markup .= '</table>' . "\n";

        file_put_contents('/var/www/html/wp-content/themes/simplicity/directory.html', $markup);
}

class PersonCollection
{
        public $people;

        public function __construct($data)
        {
                foreach($data as $person)
                {
                        $this->people[] = new Person($person);
                }

                $this->sort();
        }

        public function sort()
        {
                usort($this->people, function($a, $b) {
                        return strcmp($a->last_name, $b->last_name);
                });
        }
}

class Person   //you can edit this to pull different variables from AD
{
        public $first_name = "";
        public $last_name = "";
        public $title  = "";
        public $phone = "";
        public $email = "";

        public function __construct($data)
        {
                if( isset($data['givenname']) ){
                        $this->first_name = $data['givenname'][0];
                }

                if( isset($data['sn']) ){
                        $this->last_name = $data['sn'][0];
                }
                if( isset($data['title']) ){
                        $this->title = $data['title'][0];
                }
                if( isset($data['telephonenumber']) ){
                        $this->phone = $data['telephonenumber'][0];
                }
                if( isset($data['mail']) ){
                        $this->email = strtolower($data['mail'][0]);
                }
        }

        public function getName()
        {
                return $this->first_name . " " . $this->last_name;
        }
}

?>

