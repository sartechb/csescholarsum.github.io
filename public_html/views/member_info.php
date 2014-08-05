<?php
# Defines all information for members
#
#

include('../parse/parse.php');
include('../twig.php');
include('../config.php');

# Uncomment these for testing purposes
$_SESSION['type'] = 'Admin';
$_SESSION['USER_UNIQ'] = 'stepa';

# Check if logged in
if (!isset($_SESSION['type']))
{ 
    # Redirect home
    header("Location: {$siteurl}");
}

# Is logged in 
else if ($_SESSION['type'] == "NotLoggedIn")
{
    # Redirect home
    header("Location: {$siteurl}");
}

else{
    # Am logged in
    $parse = new ParseQuery('People');
    $parse->whereEqualTo('uniqname', $_SESSION['USER_UNIQ']);
    $member = $parse->find();


    // Parse returns this weird array. I just want the person.
    $member = $member->results[0];

    // Make person member if they should be
    if($member->corporate >= $corporateevents and $member->social >= $socialevents and $member->service >= $serviceevents and $member->type == 'Prospective'){
        $object = new ParseObject('People');
        $object->type = 'Member';
        $object->update($member->objectId);

        // Regrab member object so its updated.
        $parse = new ParseQuery('People');
        $parse->whereEqualTo('uniqname', $_SESSION['USER_UNIQ']);
        $member = $parse->find();

        $member = $member->results[0];

    }


    echo $twig->render('member_info.phtml', array('member' => $member, 'login' => $_SESSION['type'], 'serviceevents' => $serviceevents, 'socialevents' => $socialevents, 'corporateevents' => $corporateevents));
}
?>
