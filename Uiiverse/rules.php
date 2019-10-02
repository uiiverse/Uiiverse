<?php
require_once('lib/htm.php');
require_once('lib/htmUsers.php');
$tabTitle = 'Code of Conduct - Uiiverse';
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
<div class="main-column"><div class="post-list-outline">
  <h2 class="label">Uiiverse Code of Conduct</h2>
  <div id="guide" class="help-content">
    <div class="num1">
      <h2>Uiiverse Code of Conduct</h2>
      <p>Because of <i>most other</i> Miiverse clones being either complete free-for-alls or too strict, I've decided to come up with a reasonable rulebook.<br>
<br>It is somewhat simiilar to Miiverse's Code of Conduct, so if you've ever used the original service, then you'll already have a basic understanding of it.</p>

      <h3>Age</h3>
      <p>People over the age of 13 are allowed to sign up for Uiiverse, and are encouraged to use the service.</p>
      <p>Any users under the age of 13 must have an trusted guardian (such as a parent) monitor their usage of the site at <b>all times</b>. There are no exceptions to this rule, and if an underage user makes an account without permission from an individual over the age of 18, they will be restricted from the service.</p>
      <p class="guide-img7 guide-img"><img src="https://f.coka.la/gp2FoV.png"></p>

      <h3>Bullying/discrimination</h3>
      <p>Bullying or harassing a user (i.e. stalking their profile, commenting hateful things on their posts, or consistently posting about said user on <i>your</i> profile), is against the rules and you <b>will</b> get restricted from this service for a time period between an hour and two weeks for doing so.</p>
      <p>Additionally, discrimination like racism/sexism is also against the rules. We do not tolerate hateful content against any groups on my website. This also means that you won't be able to argue about it, either.</p>
      
      <p class="guide-img2 guide-img"><img src="http://web.archive.org/web/20170721020403im_/https://d13ph7xrk1ee39.cloudfront.net/img/welcome/welcome5-4.png?u8aQ6T-yrUNQT256pgWRTA"></p>

      <h3>Personal information</h3>
      <p>Never fully trust anyone on the internet. It's dangerous out here.</p>
      <p>Don't <b>ever</b> share your last name, city, state, home address, phone number, or any other personally identifiable information.</p>
      <p>This is to prevent leakage of personal info like doxxing, as it's unfortunately common on the internet, and particularly, Miiverse clones.</p>
      <p class="guide-img3 guide-img"><img src="http://web.archive.org/web/20170721020403im_/https://d13ph7xrk1ee39.cloudfront.net/img/welcome/welcome5-2.png?m9YlOUx_3L_El0y4T9HpnQ"></p>

      <h3>Offensive words</h3>
      <p>Some may already know about this rule, but we're gonna add it here anyway.</p>
      <p>Profanity is against the rules here on Uiiverse.</p>
      <p>This service is supposed to emulate Miiverse <b>as much as possible</b>, and making the rules as light as Closedverse's would ruin the whole vibe.</p>
      <p>It's also benificial for the younger users and their families, because they can now discuss games in peace again!</p>
      <p class="guide-img4 guide-img"><img src="http://web.archive.org/web/20170721020403im_/https://d13ph7xrk1ee39.cloudfront.net/img/welcome/welcome5-3.png?O08_Av9Ee7Z3YQ4gexYV0Q"></p>

      <h3>NSFW & NSFL content</h3>
      <p>Again, there are children on this site.</p>
      <p>NSFW content (not safe for work) is against the rules. It's not allowed in your activity feed like Closedverse, either. It doesn't matter <i>how</i> you post the content. Keep those disgusting images to yourself.</p>
      <p>NSFL content (not safe for <b>life</b>) is also against the rules. Please refrain from posting gore, violent content, and otherwise mentally disturbing images. We don't wish for people's lives to be ruined, especially when they're just trying to use an innocent gaming site.</p>
      <h3>"Serious" and otherwise off-topic content</h3>
      <p>This may disappoint some people, but there isn't any community speficially for serious discussions here. In fact, there's no communities dedicated to <i>any</i> subject except for the game you're playing.</p>
      <p>Because we want to keep the Miiverse spirit alive, We've just made the site itself strictly about gaming.</p>
      <p>Even though the site may be <b>meant</b> for gaming discussion, that doesn't mean that we're completely removing your American rights. Freedom of speech is still a thing here.</p>
      <p>For example, the New Super Luigi U community isn't just about New Super Luigi U. People can talk about anything they want there, except for what Closedverse users deem as "serious" posts.</p>
<p>That means that you can't start political debates, talk about your depression, or defame other users for your own benefit.</p>
      <p>Additionally, if you have suicidal tendencies, seek professional help or speak to your loved ones about it. Don't just keep it a secret or tell strangers on the internet.</p>
      <h3>Code of Conduct Violations</h3>
      <p>Our true goal with this is to keep the spirit of Miiverse alive. That means making it fun and enjoyable for absolutely everyone. No exceptions</p>
      <p>In the event that someone violates the rules, we will take appropriate action, up to and including blocking the offending user's device.</p>
      <p class="guide-img5 guide-img"><img src="http://web.archive.org/web/20170721020403im_/https://d13ph7xrk1ee39.cloudfront.net/img/welcome/welcome5-6.png?p9JgI-aYt8p5v_e2FgMn7g"></p>
    </div>

    <div class="num2">
      <h2>A Few Reminders</h2>
      <p>So, let's go over the reasons your posts may be deleted. We'll be covering them all here.</p>
      
      <h3>Violation Types</h3>
      <ul>
        <li>Personal Information
            <p>Personal information includes but is not limited to your e-mail address, home address, work or school name, and phone number. Never use our service as a means of setting up real-world meet-ups. Never write or ask in public for account information or IDs for other services, or any information that would allow people to be contacted directly.</p>
        </li>
        <li>Violent Content
          <p>This kind of content includes anything that depicts violence, promotes suicide, or endorses acts of cruelty or violence.</p>
        </li>
        <li>Inappropriate/Harmful
          <p>Inappropriate or harmful content includes anything that promotes dangerous behavior or illegal activities.</p>
        </li>
        <li>Hateful/Bullying
          <p>This includes any content that slanders, defames, or misrepresents another person, as well as any discriminatory, "call-outs", harassing, or abusive content.</p>
        </li>
        <li>Sexually Explicit
          <p>Sexually explicit content includes anything containing nudity, sexuality, or propositions.</p>
        </li>
        <li>Inappropriate Reporting
          <p>Intentionally misreporting or misdeleting posts that don't violate the rules is considered an offense. And I really mean it when I say that absolutely no one is above the rules, even the admins.</p>
        </li>
        <li>Other
          <p>Additional kinds of violation include intentionally posting the following:</p>
          <ul>
            <li>Content that infringes on the copyrights, intellectual-property rights, usage of likeness, or privacy rights of any third party</li>
            <li>Political content</li>
            <li>Dating. This rule is here because of other the long and disturbing history of dating on Miiverse clones. We do not endorse child predators and we want our users to be safe.</li>
            <li>Content that discusses sexuality. We are not against the LGBT community and those who belong to that community <b>will</b> be treated equally among the users, but it technically counts as sexually explicit content.</li>
            <li>Content related to the borrowing, lending, or transferal of goods or money. That means you can't spam your PayPal link with a caption like "Fund my Twitter" or "PF2M needs your help to fix Indigo".</li>
            <li>Any conduct that violates federal laws.</li>
          </ul>
        </li> 
      </ul>

      <h3>Consequences of Inappropriate Behavior</h3>
      <p>So, let's talk about the ban system.</p>
      <p>Although some users may be used to Indigo's ban system, it works a little differently here. It's more similar to Miiverse's.</p>
      <p>When a violating user gets a post of theirs deleted <b>at least</b> three times, they will recieve a warning.</p>
      <p>If said user's inappropriate behavior consists, they will recieve a temporary restriction, also known as a ban, for a certain amount of time. These restrictions can last between one hour and two weeks.</p>
      <p>Additionally, if the offending user is restricted over four times, they get another <b>final warning</b>. If they do not comply with our rules after the warning and continue to make inappropriate posts, they will eventually be <i>permanently restricted</i> from accessing the service, also known as an IP ban.</p>
      
      <h3>Disclaimers and User Responsibilities</h3>
      <p>We really appreciate you for fully reading this, and merely following the rules makes our service better for everyone, so...thank you. <br>Just remember that each user is responsible for understanding and complying with this very handbook, and no one truly is above the rules.<br>
<br>We may update this at any time, so please refer to the announcement community to stay informed of any updates.</p>
    </div>
  </div>
</div></div>
