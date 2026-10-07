<?php
/**
 * Knowledge hub — campaign planning, creative and measurement guides.
 *
 * @package Billboard_Pro
 */

return array(

	array(
		'slug'     => 'dooh-scheduling-dayparting',
		'title'    => 'Dayparting and Scheduling on LED Billboards',
		'category' => 'Campaign Planning',
		'image'    => 'billboard-2.jpg',
		'tags'     => array( 'dayparting', 'scheduling', 'DOOH' ),
		'excerpt'  => 'How flexible advertising scheduling works on LED billboards: loops, slots, dayparts, takeovers and practical schedules for different industries.',
		'content'  => <<<'EOT'
<p class="answer-box"><strong>Dayparting means scheduling your LED billboard ad to run only at the times of day that matter most to your audience</strong> — for example breakfast commuters, the lunch rush or the evening drive home. Because LED screens are digital, you can run different creatives in different dayparts on the same screen, with no print or installation cost.</p>

<p>Flexible advertising scheduling is the reason many brands move budget from static to digital outdoor. Instead of one message for four weeks, you can match message to moment. Here is how it works.</p>

<h2>Loops, slots and share of voice</h2>
<p>An LED billboard plays a loop of ads. If the loop has eight slots of 8 seconds, it repeats every 64 seconds — roughly 56 times an hour. Buying one slot gives you one-eighth share of voice. Buying two slots doubles your plays. Some screens sell longer slots or exclusive takeovers.</p>

<h2>Common dayparts</h2>
<table>
<thead><tr><th>Daypart</th><th>Typical hours</th><th>Good for</th></tr></thead>
<tbody>
<tr><td>Morning commute</td><td>6–10am</td><td>Coffee, breakfast, radio, banking, B2B</td></tr>
<tr><td>Midday</td><td>10am–3pm</td><td>Lunch offers, retail, services</td></tr>
<tr><td>Evening commute</td><td>3–7pm</td><td>Dinner, grocery, entertainment, auto</td></tr>
<tr><td>Night</td><td>7pm–midnight</td><td>Restaurants, events, nightlife, streaming</td></tr>
<tr><td>Weekend</td><td>Sat–Sun</td><td>Retail, leisure, real estate, attractions</td></tr>
</tbody>
</table>

<h2>Schedules that work</h2>
<ul>
<li><strong>Quick-service restaurant:</strong> breakfast creative 6–10am, lunch 11am–2pm, dinner 4–8pm.</li>
<li><strong>Retailer:</strong> weekday evenings plus all weekend, with a “Sale ends Sunday” message on Fridays.</li>
<li><strong>Event or launch:</strong> countdown creatives changing daily, then a takeover on launch day.</li>
<li><strong>B2B:</strong> weekday morning commute only, near business districts.</li>
</ul>

<h2>Combining scheduling with dynamic creative</h2>
<p>Some screens support triggers — switching creative by weather, temperature, sports scores or live data. See [[g:dynamic-creative-dooh|dynamic creative on DOOH]].</p>

<h2>How to brief a dayparted campaign</h2>
<ol>
<li>List your dayparts and the message for each.</li>
<li>Supply one creative per daypart at the screen's exact resolution — see [[g:led-billboard-content-specifications|creative specifications]].</li>
<li>Confirm the schedule and loop share in your proposal.</li>
<li>Ask for proof-of-play broken down by daypart.</li>
</ol>

<h2>Frequently asked questions</h2>
<h3>Does dayparting cost extra?</h3>
<p>It depends on the operator. Some include basic dayparting at no charge; premium dayparts such as commuter peaks may cost more, and buying only off-peak hours can cost less.</p>
<h3>How many creatives can I run?</h3>
<p>Most operators allow several creatives per campaign, each approved in advance. Check limits in your proposal.</p>

<p>See screens that support dayparting in our [[inv:digital|LED inventory]] or [[quote|request a dayparted plan]].</p>
EOT
	),

	array(
		'slug'     => 'led-billboard-content-specifications',
		'title'    => 'LED Billboard Creative Specs: Resolution, File Formats and Safe Areas',
		'category' => 'Campaign Planning',
		'image'    => 'hero-inner.jpg',
		'tags'     => array( 'creative specs', 'artwork', 'file formats' ),
		'excerpt'  => 'How to prepare artwork for LED billboards: exact pixel dimensions, file formats, colour, safe areas, text size and the approval process.',
		'content'  => <<<'EOT'
<p class="answer-box"><strong>Design LED billboard creative at the screen's exact pixel dimensions</strong> (for example 1400 × 400 px), export as a high-quality JPG or PNG in RGB colour, keep important content away from the edges, and use very large, high-contrast text. Roadside screens usually require a single static image per slot — no animation.</p>

<h2>1. Use the exact canvas size</h2>
<p>Every screen has a native pixel resolution. Designing at that exact size avoids scaling, which blurs text. Find it on the listing (“Resolution”) or in your proposal. Common examples:</p>
<table>
<thead><tr><th>Screen</th><th>Example canvas</th></tr></thead>
<tbody>
<tr><td>Roadside digital bulletin</td><td>1400 × 400 px</td></tr>
<tr><td>Landscape city screen</td><td>1920 × 1080 px or custom</td></tr>
<tr><td>Portrait mall screen</td><td>1080 × 1920 px</td></tr>
</tbody>
</table>
<p>Why are roadside canvases small? Because of pixel pitch and viewing distance — see [[g:pixel-pitch-explained|pixel pitch explained]].</p>

<h2>2. File format and colour</h2>
<ul>
<li><strong>JPG or PNG</strong> for static images; MP4 (H.264) only where motion is permitted.</li>
<li><strong>RGB colour</strong> (sRGB), not CMYK.</li>
<li><strong>Flattened files</strong> at 72 ppi — pixel count is what matters, not ppi.</li>
<li>Follow any file-size limit your operator specifies.</li>
</ul>

<h2>3. Safe areas and edges</h2>
<p>Keep logos and text a little in from the edges — cabinet frames and viewing angles can make edge content harder to read. Avoid thin borders that may look uneven.</p>

<h2>4. Text that can be read at speed</h2>
<ul>
<li>Aim for <strong>seven words or fewer</strong> on roadside screens.</li>
<li>Use bold, simple typefaces; avoid script and thin weights.</li>
<li>Make text large — as a rough guide, at least a quarter of the canvas height for headlines on highway screens.</li>
<li>High contrast between text and background.</li>
</ul>
<p>More in [[g:billboard-design-best-practices|billboard design best practices]].</p>

<h2>5. Test before you submit</h2>
<ul>
<li>Shrink your design to about 5% of its size: can you still read it? That simulates distance.</li>
<li>View it in greyscale to check contrast.</li>
<li>Check it on a dark background for night-time appearance — see [[g:led-billboard-brightness-nits|brightness]].</li>
</ul>

<h2>6. Approval and deadlines</h2>
<p>Submit creative a few business days before launch. The operator checks specs and content policies — see [[g:billboard-advertising-regulations|regulations]]. Once approved, artwork is scheduled remotely.</p>

<h2>Frequently asked questions</h2>
<h3>Can I use a QR code on a billboard?</h3>
<p>On roadside screens, QR codes rarely work — drivers cannot scan safely and the code is too small at distance. They can work on mall and transit screens where people stand close.</p>
<h3>Can I use the same artwork on different screens?</h3>
<p>Only if they share an aspect ratio. Most campaigns need a version per canvas size.</p>

<p>Need specs for a specific screen? Every listing in our [[inv:digital|LED inventory]] shows its resolution, or [[contact|ask our creative team]].</p>
EOT
	),

	array(
		'slug'     => 'billboard-design-best-practices',
		'title'    => 'Billboard Design Best Practices: Creative That Works at a Glance',
		'category' => 'Campaign Planning',
		'image'    => 'billboard-1.jpg',
		'tags'     => array( 'billboard design', 'creative', 'copywriting' ),
		'excerpt'  => 'Practical rules for billboard creative: one idea, seven words, bold type, high contrast, strong branding and a clear next step.',
		'content'  => <<<'EOT'
<p class="answer-box"><strong>Great billboard creative communicates one idea in a few seconds.</strong> Use seven words or fewer, a single strong image, bold type, high contrast and an unmistakable brand. If someone driving past can't grasp it in a glance, it is too complicated.</p>

<h2>The rules that matter</h2>
<ol>
<li><strong>One idea.</strong> Not three benefits — one.</li>
<li><strong>Seven words or fewer.</strong> Drivers have seconds, not minutes.</li>
<li><strong>Big, bold type.</strong> Simple sans-serif typefaces in heavy weights.</li>
<li><strong>High contrast.</strong> Dark on light or light on dark. Avoid mid-tone combinations.</li>
<li><strong>A single focal image.</strong> One product, one face, one visual.</li>
<li><strong>Brand it clearly.</strong> Logo large enough to read; brand colours help recognition.</li>
<li><strong>A simple next step.</strong> A short web address, “Exit 14” or a store name — not a phone number and a paragraph.</li>
</ol>

<h2>Designing for LED screens</h2>
<ul>
<li>Design at the exact canvas size — see [[g:led-billboard-content-specifications|creative specifications]].</li>
<li>Use the flexibility: different messages by daypart — see [[g:dooh-scheduling-dayparting|dayparting]].</li>
<li>Remember night viewing: avoid large areas of pure white.</li>
<li>Ads are seen in sequence with others — a bold, simple layout stands out in a loop.</li>
</ul>

<h2>Common mistakes</h2>
<ul>
<li>Too much text or a full phone number.</li>
<li>Small logos in a corner.</li>
<li>Busy photography where the product gets lost.</li>
<li>Low-contrast colour pairs such as red on green or grey on white.</li>
<li>Reusing print or social ads without adapting them.</li>
</ul>

<h2>Test like a driver</h2>
<p>Print your design small or view it on a phone at arm's length for three seconds. Ask someone what it said. If they can't tell you, simplify.</p>

<h2>Frequently asked questions</h2>
<h3>What is the 7-word rule for billboards?</h3>
<p>A widely used guideline that billboard copy should be seven words or fewer, so it can be read at a glance by people in moving vehicles.</p>
<h3>What colours work best on billboards?</h3>
<p>High-contrast combinations — black and yellow, white on dark blue, dark text on bright yellow or orange — are among the most legible at distance.</p>

<p>Want a second opinion on your artwork? [[contact|Send it to our team]] when you [[quote|request a quote]].</p>
EOT
	),

	array(
		'slug'     => 'measuring-billboard-advertising-roi',
		'title'    => 'Measuring Outdoor Advertising: Impressions, Reach, Frequency and ROI',
		'category' => 'Campaign Planning',
		'image'    => 'hero-campaign.jpg',
		'tags'     => array( 'measurement', 'ROI', 'impressions', 'attribution' ),
		'excerpt'  => 'How billboard audiences are estimated, what impressions, reach and frequency mean, and practical ways to connect outdoor to website visits, store traffic and sales.',
		'content'  => <<<'EOT'
<p class="answer-box"><strong>Outdoor advertising is measured with audience estimates — impressions, reach and frequency — plus proof of play, and increasingly with attribution studies that link exposure to website visits, store visits or sales.</strong> Combining simple tactics (unique URLs, search lift, sales comparisons) with operator reporting gives a realistic view of ROI.</p>

<h2>Key metrics</h2>
<ul>
<li><strong>Impressions:</strong> the estimated number of times people are likely to notice the ad. In the US, audience estimates are commonly based on Geopath methodology, which models traffic, visibility and the likelihood of noticing an ad.</li>
<li><strong>Reach:</strong> how many different people see it at least once.</li>
<li><strong>Frequency:</strong> how many times on average each person sees it.</li>
<li><strong>CPM:</strong> cost per thousand impressions — see [[g:how-much-does-billboard-advertising-cost|billboard costs]].</li>
<li><strong>Proof of play:</strong> logs from LED screens showing how many times your ad ran.</li>
</ul>

<h2>How impressions are estimated</h2>
<p>Estimates start with traffic counts for roads and pedestrian counts for venues, then adjust for visibility — distance, angle, obstructions, lighting and time in view. That is why two boards on the same road can have very different impressions. Our listings show estimated daily and monthly impressions, visibility distance and average viewing time.</p>

<h2>Connecting outdoor to results</h2>
<ol>
<li><strong>Before/after comparison.</strong> Compare sales, enquiries or footfall in the campaign period and area with a previous period or a similar area without billboards.</li>
<li><strong>Search lift.</strong> Track branded search volume in the campaign region.</li>
<li><strong>Unique URLs or offer codes</strong> shown only on the billboard.</li>
<li><strong>Mobile location studies.</strong> Some providers compare store visits of devices seen near a billboard with a control group.</li>
<li><strong>Brand lift surveys.</strong> Measure awareness and consideration before and after.</li>
</ol>

<h2>Setting realistic expectations</h2>
<p>Outdoor primarily builds awareness and familiarity, and it boosts the performance of other channels. Measure it against those goals, not only last-click conversions.</p>

<h2>Frequently asked questions</h2>
<h3>How accurate are billboard impression numbers?</h3>
<p>They are modelled estimates, not exact counts. Use them to compare sites consistently and combine them with your own business results.</p>
<h3>Can I track billboard conversions online?</h3>
<p>Partially — through unique URLs, codes, search lift and location-based studies. Direct click tracking isn't possible because billboards aren't clickable.</p>

<p>See audience estimates for every site in our [[inv|inventory]], or browse our [[cs|case studies]].</p>
EOT
	),

	array(
		'slug'     => 'programmatic-dooh',
		'title'    => 'Programmatic DOOH Explained: Buying LED Billboards Like Digital Media',
		'category' => 'Campaign Planning',
		'image'    => 'billboard-4.jpg',
		'tags'     => array( 'programmatic', 'DOOH', 'automation' ),
		'excerpt'  => 'What programmatic digital out-of-home is, how it differs from direct buying, and when it makes sense for advertisers.',
		'content'  => <<<'EOT'
<p class="answer-box"><strong>Programmatic DOOH is buying digital out-of-home ad plays automatically through software</strong> — usually a demand-side platform (DSP) — using targeting rules such as time, location and audience data. It offers flexibility and fast activation; direct buying offers guaranteed placements and premium sites.</p>

<h2>How it works</h2>
<ol>
<li>Screen owners make inventory available through a supply-side platform (SSP).</li>
<li>Advertisers set targeting, budget and creative in a DSP.</li>
<li>When a screen has an available play that matches, the DSP buys it — sometimes by auction, sometimes at a fixed price.</li>
<li>Reporting shows plays and estimated impressions.</li>
</ol>

<h2>Direct vs programmatic</h2>
<table>
<thead><tr><th></th><th>Direct buy</th><th>Programmatic</th></tr></thead>
<tbody>
<tr><td>Guaranteed sites and dates</td><td>Yes</td><td>Not always</td></tr>
<tr><td>Access to premium screens</td><td>Full</td><td>Varies</td></tr>
<tr><td>Speed of activation</td><td>Days</td><td>Hours</td></tr>
<tr><td>Data-driven triggers</td><td>Limited</td><td>Strong</td></tr>
<tr><td>Best for</td><td>Brand campaigns, key sites</td><td>Flexible, data-led, multi-channel buys</td></tr>
</tbody>
</table>

<h2>When to use it</h2>
<ul>
<li>You already buy programmatic media and want outdoor in the same plan.</li>
<li>You want to switch on screens based on triggers such as weather.</li>
<li>You need short, flexible activations across many screens.</li>
</ul>
<p>For flagship sites and guaranteed share of voice, direct buying usually remains the better option. See [[g:dooh-scheduling-dayparting|scheduling]] and [[g:dynamic-creative-dooh|dynamic creative]].</p>

<h2>Frequently asked questions</h2>
<h3>Is programmatic DOOH cheaper?</h3>
<p>Not necessarily. It can be efficient for flexible buys, but premium screens and guaranteed delivery are often better value bought directly.</p>
<h3>Can I target specific audiences?</h3>
<p>You target screens and times where your audience is likely to be, often informed by anonymised mobility data — not individuals.</p>

<p>Want a direct plan with guaranteed screens? [[quote|Request a proposal]].</p>
EOT
	),

	array(
		'slug'     => 'dynamic-creative-dooh',
		'title'    => 'Dynamic Creative on LED Billboards: Weather, Countdowns and Live Data',
		'category' => 'Campaign Planning',
		'image'    => 'hero-city.jpg',
		'tags'     => array( 'dynamic creative', 'contextual advertising' ),
		'excerpt'  => 'How LED billboards can change creative automatically based on time, weather, countdowns, scores and live data — with ideas and practical tips.',
		'content'  => <<<'EOT'
<p class="answer-box"><strong>Dynamic creative changes what an LED billboard shows based on rules or live data</strong> — the time of day, the weather, a countdown to a launch, sports results or prices. Because the screen is digital, the right message appears at the right moment without any manual change.</p>

<h2>Popular triggers</h2>
<ul>
<li><strong>Time and day</strong> — the simplest form; see [[g:dooh-scheduling-dayparting|dayparting]].</li>
<li><strong>Weather</strong> — hot drinks when it is cold, sunscreen when it is sunny.</li>
<li><strong>Countdowns</strong> — days until a launch, sale or event.</li>
<li><strong>Live data</strong> — scores, prices, wait times, social mentions (subject to approval).</li>
<li><strong>Location</strong> — different versions per screen, naming the nearest store.</li>
</ul>

<h2>Making it work</h2>
<ol>
<li>Agree the rules and every possible version with the operator upfront.</li>
<li>Pre-approve all creatives; live-data templates usually need approval of the template and data source.</li>
<li>Keep each version simple — dynamic is not an excuse for clutter. See [[g:billboard-design-best-practices|design best practices]].</li>
<li>On roadside screens, each version must still be a static image that complies with dwell-time rules — see [[g:billboard-advertising-regulations|regulations]].</li>
</ol>

<h2>Ideas by industry</h2>
<ul>
<li><strong>Retail:</strong> “Sale ends in 3 days” → “Last day!”.</li>
<li><strong>Food and drink:</strong> weather-led menu items.</li>
<li><strong>Healthcare:</strong> live ER wait times (where permitted).</li>
<li><strong>Media:</strong> episode countdowns and premiere-night takeovers.</li>
</ul>

<h2>Frequently asked questions</h2>
<h3>Does dynamic creative cost more?</h3>
<p>Basic time-based switching is often included. Data integrations and custom templates may involve setup fees.</p>

<p>Explore screens that support dynamic campaigns: [[inv:digital|LED inventory]].</p>
EOT
	),

	array(
		'slug'     => 'billboard-campaign-checklist',
		'title'    => 'Billboard Campaign Checklist: From Brief to Go-Live',
		'category' => 'Campaign Planning',
		'image'    => 'billboard-3.jpg',
		'tags'     => array( 'checklist', 'campaign planning', 'timeline' ),
		'excerpt'  => 'A step-by-step checklist and timeline for planning, booking, producing and measuring an LED or static billboard campaign.',
		'content'  => <<<'EOT'
<p class="answer-box"><strong>A typical LED billboard campaign can go from brief to live in one to three weeks:</strong> define goals and audience, shortlist sites, confirm availability and price, produce creative to spec, get approval, launch and measure. Static campaigns need extra time for printing and installation.</p>

<h2>Timeline at a glance</h2>
<table>
<thead><tr><th>When</th><th>Step</th></tr></thead>
<tbody>
<tr><td>4–6 weeks before</td><td>Brief, budget, audience and markets</td></tr>
<tr><td>3–5 weeks before</td><td>Shortlist sites, receive proposal, confirm booking</td></tr>
<tr><td>2–3 weeks before</td><td>Creative design to exact specs</td></tr>
<tr><td>1 week before</td><td>Submit artwork for approval (static: print and schedule install)</td></tr>
<tr><td>Launch</td><td>Go live; receive confirmation photos</td></tr>
<tr><td>During / after</td><td>Proof of play, performance review</td></tr>
</tbody>
</table>

<h2>The checklist</h2>
<h3>1. Brief</h3>
<ul>
<li>Objective: awareness, launch, traffic, recruitment?</li>
<li>Audience and markets.</li>
<li>Dates and budget.</li>
</ul>
<h3>2. Planning</h3>
<ul>
<li>Shortlist sites — see [[g:how-to-choose-billboard-location|choosing a location]].</li>
<li>Choose formats — see [[g:digital-vs-static-billboards|digital vs static]].</li>
<li>Decide dayparts — see [[g:dooh-scheduling-dayparting|dayparting]].</li>
</ul>
<h3>3. Booking</h3>
<ul>
<li>Confirm sites, dates, loop share and price.</li>
<li>Ask about uptime commitments, make-goods and reporting.</li>
<li>Check category restrictions — see [[g:billboard-advertising-regulations|regulations]].</li>
</ul>
<h3>4. Creative</h3>
<ul>
<li>Design to each screen's canvas — see [[g:led-billboard-content-specifications|creative specs]].</li>
<li>Apply [[g:billboard-design-best-practices|design best practices]].</li>
<li>Submit for approval with time to spare.</li>
</ul>
<h3>5. Launch and measure</h3>
<ul>
<li>Get go-live photos and proof of play.</li>
<li>Track results — see [[g:measuring-billboard-advertising-roi|measuring outdoor]].</li>
</ul>

<h2>Frequently asked questions</h2>
<h3>How far in advance should I book a billboard?</h3>
<p>Four to eight weeks is comfortable for most campaigns; popular sites and seasons sell out earlier. LED screens can sometimes be booked at short notice.</p>

<p>Ready to start? [[quote|Request a quote]] or [[inv|browse inventory]].</p>
EOT
	),
);
