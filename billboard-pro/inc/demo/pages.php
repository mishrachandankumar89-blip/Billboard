<?php
/**
 * Core pages.
 *
 * @package Billboard_Pro
 */

$glossary = array(
	'Ambient light sensor' => 'A sensor that measures surrounding light so an LED screen can brighten in daylight and dim at night automatically. See [[g:led-billboard-brightness-nits|LED brightness]].',
	'Aspect ratio'         => 'The ratio of a screen\'s width to its height, such as 3.5:1 for many digital bulletins or 16:9 for city screens.',
	'Audience estimate'    => 'A modelled number of people likely to notice an ad, based on traffic, visibility and viewing time.',
	'Bulletin'             => 'The largest standard billboard format, commonly 14 × 48 ft, found on highways and major roads. See [[g:billboard-sizes-formats|billboard sizes]].',
	'Cabinet'              => 'The weather-sealed enclosure that holds LED modules, power supplies and receiving cards. Cabinets tile together to form a screen.',
	'COB (chip-on-board)'  => 'An LED packaging method that mounts bare chips directly on the circuit board under a protective coating.',
	'Contrast ratio'       => 'The difference between the brightest white and darkest black a screen can show. High contrast keeps images readable in sunlight.',
	'CPM'                  => 'Cost per thousand impressions — the standard way to compare value across billboards. See [[g:how-much-does-billboard-advertising-cost|billboard costs]].',
	'Creative'             => 'The artwork or advertisement shown on a billboard.',
	'Cross-read'           => 'A billboard on the left side of the road (in the US), read across oncoming lanes.',
	'Daypart'              => 'A segment of the day, such as morning commute, used to schedule ads. See [[g:dooh-scheduling-dayparting|dayparting]].',
	'DIP LED'              => 'Dual in-line package: a through-hole LED lamp. Each pixel uses separate red, green and blue lamps. See [[g:smd-vs-dip-led|SMD vs DIP]].',
	'DOOH'                 => 'Digital out-of-home: any digital advertising screen in public places, including LED billboards, transit and mall screens.',
	'Dwell time'           => 'How long a viewer is exposed to an ad — short on highways, long in transit stations.',
	'Dynamic creative'     => 'Creative that changes automatically based on rules or data, such as weather or countdowns. See [[g:dynamic-creative-dooh|dynamic creative]].',
	'Flight'               => 'The scheduled run of a campaign, from start date to end date.',
	'Frequency'            => 'The average number of times each person sees an ad during a campaign.',
	'Geopath'              => 'The US organisation that provides audience measurement standards for out-of-home advertising.',
	'Illumination'         => 'Lighting used to make static billboards visible at night, typically LED floodlights.',
	'Impression'           => 'One estimated instance of a person noticing an ad.',
	'IP rating'            => 'Ingress Protection code describing resistance to dust and water, such as IP65. See [[g:led-billboard-weatherproofing-ip65|IP ratings]].',
	'Loop'                 => 'The repeating sequence of ads on a digital screen.',
	'Make-good'            => 'Extra plays or credit given when a screen fails to deliver what was booked.',
	'Module'               => 'A small circuit board with LEDs and driver chips; the building block of an LED cabinet.',
	'Nit'                  => 'A unit of brightness equal to one candela per square metre. Outdoor LED billboards are often 5,000+ nits.',
	'Pixel pitch'          => 'The distance in millimetres between neighbouring pixel centres. See [[g:pixel-pitch-explained|pixel pitch explained]].',
	'Poster'               => 'A mid-size printed billboard, often around 10.5 × 22.8 ft.',
	'Programmatic DOOH'    => 'Buying digital out-of-home plays automatically through software. See [[g:programmatic-dooh|programmatic DOOH]].',
	'Proof of play'        => 'Logs from a digital screen showing when and how often each ad ran.',
	'Reach'                => 'The number of different people who see an ad at least once.',
	'Refresh rate'         => 'How many times per second an LED screen redraws its image; higher rates reduce flicker on camera.',
	'Right-hand read'      => 'A billboard on the right side of the road (in the US), closer to the driver\'s natural line of sight.',
	'Share of voice'       => 'Your proportion of a digital loop — for example one slot in a loop of eight is a one-eighth share.',
	'Showing'              => 'A package of billboards in a market designed to reach a share of the population.',
	'Slot'                 => 'One position in a digital loop, usually 6–10 seconds long.',
	'SMD LED'              => 'Surface-mounted device: an LED package combining red, green and blue chips. Standard in modern screens.',
	'Spectacular'          => 'An oversized, custom billboard or LED screen in a landmark location.',
	'Takeover'             => 'Exclusive use of a digital screen for a set period.',
	'Viewing angle'        => 'The range of angles from which a screen keeps acceptable brightness and colour. See [[g:viewing-angles-led-screens|viewing angles]].',
	'Wallscape'            => 'An ad painted or mounted directly on the side of a building.',
);
$gloss_html = '<p class="answer-box">Plain-English definitions of the terms you will meet when planning LED billboard and outdoor advertising campaigns. Each term links to an in-depth guide where one exists.</p>';
$letters    = array();
foreach ( $glossary as $term => $def ) {
	$letters[ strtoupper( $term[0] ) ][ $term ] = $def;
}
foreach ( $letters as $letter => $terms ) {
	$gloss_html .= '<h2>' . $letter . '</h2>';
	foreach ( $terms as $term => $def ) {
		$gloss_html .= '<h3>' . $term . '</h3><p>' . $def . '</p>';
	}
}
$gloss_html .= '<p>Still have questions? Read our [[p:faq|FAQ]] or [[contact|ask the team]].</p>';

return array(
	'home'     => array( 'title' => 'Home', 'slug' => 'home', 'content' => '' ),
	'guides'   => array( 'title' => 'LED Billboard Guides', 'slug' => 'guides', 'content' => '' ),
	'quote'    => array(
		'title'    => 'Request a Quote',
		'slug'     => 'request-a-quote',
		'template' => 'page-templates/template-quote.php',
		'excerpt'  => 'Share your goals, dates and budget. A campaign planner replies with available LED screens, audience data and a tailored proposal within one business day.',
		'content'  => '<p>Not sure which screens you need? That is fine — tell us your audience and objective and we will recommend a plan. You can also browse the [[inv|full billboard inventory]] first.</p>',
	),
	'contact'  => array(
		'title'    => 'Contact Us',
		'slug'     => 'contact',
		'template' => 'page-templates/template-contact.php',
		'excerpt'  => 'Talk to a campaign planner about LED billboard screens, locations, pricing and partnerships.',
		'content'  => '<h2>Talk to a planner</h2><p>Whether you are planning your first billboard or a multi-city LED campaign, our planners can help you choose screens, build a schedule and prepare creative. Landowners and property owners interested in hosting a screen are welcome to get in touch too.</p><p>Looking for answers fast? Try our [[p:faq|FAQ]] or the [[p:guides|LED billboard guides]].</p>',
	),
	'led'      => array(
		'title'   => 'LED Billboard Screens',
		'slug'    => 'led-billboard-screens',
		'image'   => 'led-closeup.jpg',
		'excerpt' => 'Large-format digital displays for outdoor advertising, brand promotion and high-visibility media campaigns — bright in sunlight, flexible to schedule and built for the outdoors.',
		'content' => <<<'EOT'
<p class="answer-box"><strong>Billboard LED screens are large-format digital displays designed for outdoor advertising, brand promotion and high-visibility media campaigns.</strong> Compared with traditional static billboards or conventional illuminated signage, LED billboard displays provide dynamic content playback, higher brightness under sunlight and flexible advertising management. They are widely used along highways, urban roads, commercial districts and public spaces where visibility and audience reach are critical.</p>

<h2>Why brands choose LED billboard screens</h2>

<h3>Exceptional daylight performance</h3>
<p>Outdoor LED screens are engineered to stay vivid when the sun is shining directly on them — typically rated at 5,000 nits or more, with high-contrast modules and louvres that cut glare. At night, ambient light sensors dim the screen automatically so it stays comfortable to view and compliant with local rules. Read more in [[g:led-billboard-brightness-nits|LED Billboard Brightness: Nits, Daylight Visibility and Auto-Dimming]].</p>

<h3>Flexible advertising scheduling</h3>
<p>Because content is digital, you can book shorter flights, run several creatives, and schedule by daypart — a breakfast message for the morning commute and a different one for the drive home. No printing, no installation, and creative updates can be made remotely after approval. See [[g:dooh-scheduling-dayparting|Dayparting and Scheduling on LED Billboards]].</p>

<h3>Consistent image quality at any angle</h3>
<p>Modern SMD LEDs and careful calibration keep colour and brightness consistent across wide horizontal viewing angles, so drivers in every lane and pedestrians walking past see the same clear image. See [[g:viewing-angles-led-screens|Viewing Angles]] and [[g:smd-vs-dip-led|SMD vs DIP LEDs]].</p>

<h3>Durable outdoor construction</h3>
<p>Weather-sealed cabinets — commonly IP65 at the front — protect electronics from rain and dust, while thermal management, surge protection and 24/7 remote monitoring keep screens running through heat, cold and storms. See [[g:led-billboard-weatherproofing-ip65|IP Ratings and Weatherproofing]] and [[g:led-billboard-lifespan-maintenance|Lifespan and Maintenance]].</p>

<h3>Customizable screen formats</h3>
<p>LED screens are built from modular cabinets, so they can be configured to almost any size and aspect ratio — classic 14 × 48 ft highway bulletins, portrait towers, building façades and close-range mall displays, each with a pixel pitch matched to its viewing distance. See [[g:pixel-pitch-explained|Pixel Pitch Explained]] and [[g:billboard-sizes-formats|Billboard Sizes and Formats]].</p>

<h2>LED billboard vs static billboard vs illuminated sign</h2>
<table>
<thead><tr><th></th><th>LED billboard screen</th><th>Static billboard</th><th>Conventional lightbox</th></tr></thead>
<tbody>
<tr><td>Content</td><td>Dynamic, multiple creatives</td><td>One printed design</td><td>One printed design</td></tr>
<tr><td>Daylight visibility</td><td>Excellent</td><td>Good</td><td>Weak in direct sun</td></tr>
<tr><td>Night visibility</td><td>Self-lit, auto-dimmed</td><td>Depends on floodlights</td><td>Backlit</td></tr>
<tr><td>Changing the message</td><td>Remote, minutes</td><td>Print + install</td><td>Print + install</td></tr>
<tr><td>Scheduling</td><td>By daypart, day, week</td><td>Four-week periods</td><td>Long term</td></tr>
<tr><td>Advertisers per face</td><td>Several (shared loop)</td><td>One</td><td>One</td></tr>
</tbody>
</table>
<p>For a deeper comparison read [[g:digital-vs-static-billboards|Digital vs Static Billboards]].</p>

<h2>Where LED billboard screens work best</h2>
<ul>
<li><strong>Highways and arterial roads</strong> — maximum reach and repeat exposure. Browse [[type:led-highway-billboard|LED highway billboards]].</li>
<li><strong>Urban roads and intersections</strong> — dense, mixed audiences. Browse [[type:building-led-screen|building-mounted LED screens]].</li>
<li><strong>Commercial districts</strong> — landmark impact for launches. Browse [[type:led-spectacular|city-center LED spectaculars]].</li>
<li><strong>Public spaces, malls and transit</strong> — close-range, high-dwell audiences. Browse [[type:mall-screen|mall screens]] and [[type:transit-screen|transit screens]].</li>
</ul>

<h2>How to advertise on an LED billboard</h2>
<ol>
<li><strong>Find a screen</strong> — filter our [[inv:digital|LED inventory]] by city, size, budget and availability.</li>
<li><strong>Request a quote</strong> — tell us your dates; we confirm availability and pricing.</li>
<li><strong>Send creative</strong> — sized to the screen's resolution (see [[g:led-billboard-content-specifications|creative specs]]).</li>
<li><strong>Go live</strong> — with go-live photos and proof-of-play reporting.</li>
</ol>

<h2>Frequently asked questions</h2>
<h3>What is a billboard LED screen?</h3>
<p>A large outdoor digital display made from modules of red, green and blue LEDs, used to show advertising that can change and be scheduled remotely. Learn more in [[g:what-is-an-led-billboard|What Is an LED Billboard?]].</p>
<h3>How bright is an outdoor LED billboard?</h3>
<p>Typically around 5,000 nits or more for sunlight readability, dimming automatically at night.</p>
<h3>How much does it cost to advertise on an LED billboard?</h3>
<p>It depends on market, audience and share of the loop. Each listing shows a starting price; see [[g:how-much-does-billboard-advertising-cost|billboard advertising costs]] for a full breakdown.</p>
<h3>Can I own an LED billboard screen?</h3>
<p>Yes, subject to permits and site suitability. See [[g:buying-an-led-billboard-screen|Buying an LED Billboard Screen]].</p>

<p>Ready to put your brand on a screen? [[inv:digital|Explore LED screens]] or [[quote|request a quote]].</p>
EOT
	),
	'faq'      => array(
		'title'   => 'Frequently Asked Questions',
		'slug'    => 'faq',
		'excerpt' => 'Answers to the most common questions about LED billboard advertising — pricing, booking, creative, scheduling and reporting.',
		'content' => <<<'EOT'
<p class="answer-box">Quick answers to the questions advertisers ask most about LED billboard screens and outdoor advertising. Each answer links to a detailed guide if you want to go deeper.</p>

<h2>Frequently asked questions</h2>
<h3>How much does billboard advertising cost?</h3>
<p>Prices depend on market, audience size, format and campaign length. A share of a roadside LED billboard for four weeks can start in the low thousands of dollars in many markets. See [[g:how-much-does-billboard-advertising-cost|billboard costs]].</p>
<h3>What is the minimum booking?</h3>
<p>Many LED screens can be booked from one or two weeks. Static faces are usually sold in four-week periods.</p>
<h3>How many advertisers share an LED billboard?</h3>
<p>Typically six to eight in a loop on roadside screens, each shown for around 8–10 seconds. See [[g:what-is-an-led-billboard|how LED billboards work]].</p>
<h3>Can I choose the time of day my ad runs?</h3>
<p>Yes, on LED screens. See [[g:dooh-scheduling-dayparting|dayparting]].</p>
<h3>What size should my artwork be?</h3>
<p>The screen's exact pixel resolution, shown on each listing. See [[g:led-billboard-content-specifications|creative specifications]].</p>
<h3>Can my billboard ad include video?</h3>
<p>Roadside digital billboards generally may not show motion for safety reasons. Indoor and transit screens often can. See [[g:billboard-advertising-regulations|regulations]].</p>
<h3>How quickly can a campaign go live?</h3>
<p>LED campaigns can often launch within days of booking once creative is approved. Static campaigns need extra time for printing and installation.</p>
<h3>How do you measure results?</h3>
<p>With audience estimates, proof-of-play reports and, where needed, attribution methods such as search lift and store-visit studies. See [[g:measuring-billboard-advertising-roi|measuring outdoor]].</p>
<h3>Do you help with design?</h3>
<p>Yes — we review every file against the screen specs and can recommend designers. See [[g:billboard-design-best-practices|design best practices]].</p>
<h3>Which is better, digital or static?</h3>
<p>It depends on your goals. See [[g:digital-vs-static-billboards|digital vs static billboards]].</p>
<h3>Can I advertise in several cities?</h3>
<p>Yes. Browse [[locs|all markets]] and ask for a multi-city plan.</p>
<h3>What if a screen goes down during my campaign?</h3>
<p>Plays are monitored; missed plays are compensated with make-good spots or credit.</p>

<p>Didn't find your answer? [[contact|Contact us]] or check the [[p:glossary|glossary]].</p>
EOT
	),
	'glossary' => array(
		'title'   => 'LED Billboard & Outdoor Advertising Glossary',
		'slug'    => 'glossary',
		'excerpt' => 'Definitions of LED billboard and outdoor advertising terms — from pixel pitch and nits to share of voice and proof of play.',
		'content' => $gloss_html,
	),
	'about'    => array(
		'title'   => 'About Us',
		'slug'    => 'about',
		'image'   => 'hero-inner.jpg',
		'excerpt' => 'We operate LED billboard screens and outdoor advertising locations, and help brands plan campaigns that get seen.',
		'content' => <<<'EOT'
<p class="answer-box"><strong>We help brands get seen.</strong> We operate premium LED billboard screens and outdoor advertising locations, and our campaign planners help businesses of every size choose the right screens, schedule the right messages and measure what works.</p>

<h2>What we do</h2>
<ul>
<li><strong>Operate LED and static billboards</strong> on highways, city-center streets, malls and transit locations — see [[locs|our markets]].</li>
<li><strong>Plan campaigns</strong> from a single screen to multi-city networks.</li>
<li><strong>Manage everything</strong> — approvals, scheduling, printing for static faces and proof-of-play reporting.</li>
</ul>

<h2>How we work</h2>
<p>Every campaign starts with your audience and goals, not with a rate card. We recommend screens based on data — traffic, visibility, viewing time and audience profile — and we are honest when a different format or market would serve you better.</p>

<h2>Our screens</h2>
<p>Our LED billboards are chosen for daylight performance, consistent image quality at wide viewing angles and durable outdoor construction, and they are monitored around the clock. Learn more on the [[p:led-billboard-screens|LED billboard screens]] page.</p>

<h2>Learn with us</h2>
<p>We publish practical guides on everything from [[g:pixel-pitch-explained|pixel pitch]] to [[g:measuring-billboard-advertising-roi|measuring results]]. Browse the [[p:guides|knowledge hub]].</p>

<p>[[contact|Get in touch]] — we would love to hear about your next campaign.</p>
EOT
	),
	'privacy'  => array(
		'title'   => 'Privacy Policy',
		'slug'    => 'privacy-policy',
		'content' => '<p><em>Template — have this reviewed by a qualified professional and adapt it to your business and jurisdiction before publishing.</em></p><h2>Information we collect</h2><p>When you submit a quote request or contact form we collect the details you provide, such as your name, company, email address, phone number and campaign information. We also collect standard technical data such as IP address and browser type for security and analytics.</p><h2>How we use it</h2><p>We use your information to respond to your enquiry, prepare proposals, deliver campaigns and improve our services. We do not sell your personal information.</p><h2>Storage and retention</h2><p>Enquiries are stored securely in our website system and email, and retained only as long as needed for the purposes above or as required by law.</p><h2>Your rights</h2><p>You may request access to, correction of or deletion of your personal information by contacting us.</p><h2>Cookies and analytics</h2><p>We may use cookies and analytics tools to understand how our website is used.</p><h2>Contact</h2><p>Questions about this policy? [[contact|Contact us]].</p>',
	),
	'terms'    => array(
		'title'   => 'Terms of Use',
		'slug'    => 'terms',
		'content' => '<p><em>Template — have this reviewed by a qualified professional before publishing.</em></p><h2>Use of this website</h2><p>Information on this website, including billboard availability, audience estimates and prices, is provided for general guidance and may change without notice. All bookings are subject to a written proposal and contract.</p><h2>Audience estimates</h2><p>Impression and traffic figures are modelled estimates and are not guaranteed.</p><h2>Intellectual property</h2><p>Website content and images may not be reused without permission.</p><h2>Liability</h2><p>To the extent permitted by law, we are not liable for losses arising from the use of this website.</p><h2>Contact</h2><p>[[contact|Contact us]] with any questions.</p>',
	),
);
