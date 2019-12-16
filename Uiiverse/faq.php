<?php
require_once('lib/htm.php');
require_once('lib/htmUsers.php');
$tabTitle = 'Frequently Asked Questions (FAQ) - Uiiverse';
$bodyID = 'help';
printHeader('');
$get_user = $dbc->prepare('SELECT * FROM users INNER JOIN profiles ON profiles.user_id = users.user_id WHERE users.user_id = ? LIMIT 1');
	$get_user->bind_param('i', $_SESSION['user_id']);
	$get_user->execute();
	$user_result = $get_user->get_result();
	$user = $user_result->fetch_assoc();
	echo '<div id="sidebar" class="general-sidebar">';
	sidebarSetting();
echo '</div>';
?>
<div class="main-column">
  <div class="post-list-outline">
    <h2 class="label">Frequently Asked Questions (FAQ)</h2>
    <div id="guide" class="help-content">
      <div class="faq">
        <p>If you have any questions about our service, then this page is the first place to check for an answer.</p>
        
		<h2>Why did you want to remix a near-perfect Miiverse clone?</h2>
        <p>We remixed Ziiverse because it's creator, Ben, decided to donate its code to us to help speed up development.</p>

        <h2>When will Uiiverse become available for Wii, Wii U, iOS, etc?</h2>
        <p>Uiiverse currently being activly developed and we really don't have an ETA of when those will be available.</p>
        <p>Plus, iOS development is hard to do, due to Apple requiring you to pay $100 a year for App Store access and knowledge of either C+ or Swift.</p>
        
		<h2>Some things like notifications are broken!</h2>
        <p>Thats a weird glitch with our hosting, we are activly attempting to fix this bug, do not worry</p>
        <p>The same thing happens when you try to mark a post for spoilers, we will also try to fix this in the future.</p>

        <h2>Who created this site?</h2>
        <p>Uiiverse was founded by FunnyBone and meltstrap, however, the website code was made by Ben.</p>
        <p>Most of the assets belong to Nintendo and Hatena, such as CSS, images, and JavaScript.</p>
        </div>

        

        <h2>Are there any Uiiverse features that will be available on Wii U consoles and systems in the Nintendo 3DS family but not available on PCs, smartphones, or other non-Nintendo devices?</h2>
        <div>A number of features to be available in Uiiverse on Wii U and on systems in the Nintendo 3DS family are not currently available in the web version. Feature updates are planned for the future, but the details and timing of these updates are not yet announced. We appreciate your patience.<br>
<br>
Features not currently available in the web version include:<ul><li>Handwritten posts</li><li>The sending and receiving of messages</li><li>The sending and receiving of friend requests</li></ul></div>

        <h2>How do you delete a post?</h2>
        <p>Due to weird glitches with the JavaScript, this is how you get a post deleted:</p>
        <ol>
          <li>Copy the URL of the post and send it to an admin on the Uiiverse Discord Server, and they will delete the post.
            <p>We plan to get a delete button working in the near future.</p>
          </li>
        </ol>
        <p>Please note that deleting a post removes <b>ALL</b> data of that post and <b>CANNOT</b> be recovered if you change your mind.</p>

        <h2>Why did you make this website?</h2>
        <p>Uiiverse was created by meltstrap due to Wiiverse going downhill and shutting down in 2018. There were also lots of other Miiverse clones like Closedverse and Cedar. They had their own issues (like Closedverse being fully cancer and Cedar being underused.) and decided to create a true Miiverse clone.</p>
      <h3>Closedverse</h3>
      <p>Probably the most well known one. It <i>does</i> have a good programming style, it's visually pleasing, and <i>kind of</i> represents Miiverse's original purpose.</p>
      <p>The on-topic community was great. The feeling of <b>just discussing games</b> definitely brought Miiverse's spirit back. However, there was an issue with this, and it mostly lied in the Anything Goes and Serious Discussion communities.</p>
      <p>The "off-topic" people dominated the site's userbase. The rules weren't too strict either, so it was kind of just a free-for-all with them. There was drama every day, most of the English admins were bad at their jobs, and Arian was too up-close with the userbase so the feeling of authority really just disappeared eventually.</p>
      <h3>Cedar</h3>
<p>Personally, Cedar was my favorite Miiverse clone out of all of them, at least the last version was.
  <br>
  It looked almost exactly like Miiverse, it stayed loyal to the original service for the most part, and the rules, while similar to Closedverse's, were actually enforced and balanced. The userbase was also good, and Seth was a much better owner than Arian.</p>
      <p>So...what's the problem with this?</p>
      <p>There were simply <i>not enough people</i>, and the site's activity faded over the course of 2018, eventually leading to Seth killing it off for good.</p>
      <h3>Indigo</h3>
      <p>The big collab.</p>
      <p>Indigo looks aesthetically beautiful, the features are great, and post importing from Miiverse really does make the service go back to its roots, so what's the problem?</p>
      <p>Like 9,000 other clones, it's the userbase, the administration, and the fact that the owners are too personal with their users. It's just a combination of everything that made Closedverse bad. It's super strict while being a free-for-all. Like 9,000 other clones, the rules are <b>barely</b> derivative of Miiverse's. The first major "drama" that happened there started within <i>two days.</i> It's great on the outside, but terrifying on the inside.</p>
      <h3>Wiiverse</h3>
      <p>All the roots of Uiiverse can be found in Wiiverse. Wiiverse was founded by FunnyBone in early 2018 as a
	  revival to Riiverse, made by admins NintenBlox and Matrix.
	  At the beginning, Wiiverse was really good, it used an original design back in v1, but got a new look in v2 to match the early days of Miiverse.
	  The downfall of Wiiverse was staff fighting over rights and personal life. At the end, Wiiverse was shutdown by Matrix in mid-2018. Most of Wiiverse's assets were used in Uiiverse.</p>
		<h3>Uiiverse</h3>
      <p>All of those clones' problems inspired us to make an <i>exact revival</i> of Miiverse, rules and everything. We truly hope that this site ends up the way Miiverse does, and we can assure you that if we ever stop caring about it, we won't spam "SONY" or "DELL" because it's funny and break down the site's entire system.
        Instead, We'd just give it to someone we trusted or shut it down formally, but that's probably never gonna happen. We care about this site a lot and we won't let it die.</p>
      <h2>I have other questions.</h2>
      <p>No worries! If I haven't answered everything you wanted to know about this site, then you can simply just talk to the team on our <a href="https://discord.gg/5qBF8Zx">Discord Server.</a></p>
    
      <i>-FunnyBone, Co-founder of Uiiverse.</i>
    </div>
    </div>
  </div>
</div>