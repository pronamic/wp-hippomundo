<?php

    /*---------------------------------------------------------------------------------------------
    | Hippomundo Last results tool
    |---------------------------------------------------------------------------------------------
    | This PHP script can included in your page(s) in order to display the Last Result Tool
    | with sport results of all horses subscribed to a studbook. It returns only results
    | from FEI 1* to 5* competitions currently. Configuration can be done in the first
    | section of the script. This is where you set your API token and various params
    |
    | Requirements PHP 5.4 with the following extensions enabled:
    |   * json
    |   * curl
    |   * iconv (can be disabled around line 176 if necessary.)
    |
    | If you have the option to move the CSS to a separate file, this can have some performance benefits.
    |
    | Copyright: Hippomundo.com BVBA (c) 2016
    | By: Koen Calliauw
    |
    */

    /**
     * Some initial setup
     */
    $params['your_key'] = '';   // Your API token, received from Hippomundo personnel.
    $params['studbook']  = '';                              // The abbreviated name of your studbook. Your API token will be
                                                                // checked against for permissions on this studbook.

    /**
     * Optional configuration
     */
    //$params['days'] = 10;                                     // Amount of days of sport results to fetch.
                                                                // More means more results and thus longer load times and possibly
                                                                // a worse experience for the user
    //$params['minimum_placing'] = 10;                          // The minimum rank to be considered into the result set.
                                                                // Same applies as above. Keep it sane
    /**
     * Global Initialization
     */
    $results = new HippomundoResults($params);

    /**
     * Titles
     */
    $main_title = 'Sportresultaten KWPN-paarden';
    $sub_title = sprintf('Alle officiële top %d wedstrijdresultaten (FEI 1*-5*) uit de afgelopen %d dagen.', $results->minimum_placing, $results->days);


?>
<!-- Styling for Hippomundo Last Result tool -->
<style scoped>
    .hm-lrt { font-family: "Helvetica Neue", Helvetica, Arial, Verdana, sans-serif;}
    .hm-lrt table {width: 100%;background-color: transparent;color: #000000;border-width: 0 !important;margin-bottom: 0;}
    .hm-lrt table colgroup.hm-columns col:nth-child(1) {width: 15px;}
    .hm-lrt table colgroup.hm-columns col:nth-child(3) {width: 160px;}
    .hm-lrt table colgroup.hm-columns col:nth-child(4) {width: 30px;}
    .hm-lrt table colgroup.hm-columns col:nth-child(5) {width: 90px;}
    .hm-lrt table td {padding: 2px 5px;font-size: 12px;vertical-align: top;line-height: 1.8;border-width: 0 !important;}
    .hm-lrt table td.prize {text-align: right;}
    .hm-lrt table tr.hm-head td {background-color: #ff9900;color: #000079;font-weight: bold;font-size: 14px;}
    .hm-lrt table tr.hm-subhead td {background-color: #21468b;color: #ffffff;}
    .hm-lrt table tr.hm-subhead .date {font-weight: bold;}
    .hm-lrt .hm-sire {font-size: .95em;}
    .hm-lrt a,a:visited,a:active {color: #000000;text-decoration: none;border-width: 0;box-shadow: none;border-image-width:0;}
    .hm-lrt a:hover {text-decoration: underline;}
    .hm-lrt .fokker-link {font-variant: small-caps;font-size: 14px;}
    .hm-lrt .fokker-link img {width: 15px;height: 13px;display: inline-block;margin-left: 3px;margin-bottom: -1px;}
    .hm-lrt .powered {vertical-align: middle;}
    .hm-lrt .powered img {vertical-align: middle;margin: 0 15px 0 0;}
    .hm-lrt .hm-title {font-variant: small-caps;font-weight: bold;font-size: 1.2em;padding: 5px;}
    .hm-lrt .hm-subtitle {font-size: .85em;font-style: italic;padding: 5px;margin-bottom: 10px;}
    .hm-lrt .hm-powered {float: right;text-align: right;font-size: .8em;font-style: italic;}
    .hm-lrt .hm-powered a {text-decoration: underline;}
    .hm-lrt .flag {width: 16px;height: 11px;background:url(<?php echo $results->base_url;?>images/flags.png) no-repeat;}
    .hm-lrt .flag.flag-ad {background-position: -16px 0}.flag.flag-ae {background-position: -32px 0}.flag.flag-af {background-position: -48px 0}.flag.flag-ag {background-position: -64px 0}.flag.flag-ai {background-position: -80px 0}.flag.flag-al {background-position: -96px 0}.flag.flag-am {background-position: -112px 0}.flag.flag-an {background-position: -128px 0}.flag.flag-ao {background-position: -144px 0}.flag.flag-ar {background-position: -160px 0}.flag.flag-as {background-position: -176px 0}.flag.flag-at {background-position: -192px 0}.flag.flag-au {background-position: -208px 0}.flag.flag-aw {background-position: -224px 0}.flag.flag-az {background-position: -240px 0}.flag.flag-ba {background-position: 0 -11px}.flag.flag-bb {background-position: -16px -11px}.flag.flag-bd {background-position: -32px -11px}.flag.flag-be {background-position: -48px -11px}.flag.flag-bf {background-position: -64px -11px}.flag.flag-bg {background-position: -80px -11px}.flag.flag-bh {background-position: -96px -11px}.flag.flag-bi {background-position: -112px -11px}.flag.flag-bj {background-position: -128px -11px}.flag.flag-bm {background-position: -144px -11px}.flag.flag-bn {background-position: -160px -11px}.flag.flag-bo {background-position: -176px -11px}.flag.flag-br {background-position: -192px -11px}.flag.flag-bs {background-position: -208px -11px}.flag.flag-bt {background-position: -224px -11px}.flag.flag-bv {background-position: -240px -11px}.flag.flag-bw {background-position: 0 -22px}.flag.flag-by {background-position: -16px -22px}.flag.flag-bz {background-position: -32px -22px}.flag.flag-ca {background-position: -48px -22px}.flag.flag-catalonia {background-position: -64px -22px}.flag.flag-cd {background-position: -80px -22px}.flag.flag-cf {background-position: -96px -22px}.flag.flag-cg {background-position: -112px -22px}.flag.flag-ch {background-position: -128px -22px}.flag.flag-ci {background-position: -144px -22px}.flag.flag-ck {background-position: -160px -22px}.flag.flag-cl {background-position: -176px -22px}.flag.flag-cm {background-position: -192px -22px}.flag.flag-cn {background-position: -208px -22px}.flag.flag-co {background-position: -224px -22px}.flag.flag-cr {background-position: -240px -22px}.flag.flag-cu {background-position: 0 -33px}.flag.flag-cv {background-position: -16px -33px}.flag.flag-cw {background-position: -32px -33px}.flag.flag-cy {background-position: -48px -33px}.flag.flag-cz {background-position: -64px -33px}.flag.flag-de {background-position: -80px -33px}.flag.flag-dj {background-position: -96px -33px}.flag.flag-dk {background-position: -112px -33px}.flag.flag-dm {background-position: -128px -33px}.flag.flag-do {background-position: -144px -33px}.flag.flag-dz {background-position: -160px -33px}.flag.flag-ec {background-position: -176px -33px}.flag.flag-ee {background-position: -192px -33px}.flag.flag-eg {background-position: -208px -33px}.flag.flag-eh {background-position: -224px -33px}.flag.flag-england {background-position: -240px -33px}.flag.flag-er {background-position: 0 -44px}.flag.flag-es {background-position: -16px -44px}.flag.flag-et {background-position: -32px -44px}.flag.flag-eu {background-position: -48px -44px}.flag.flag-fi {background-position: -64px -44px}.flag.flag-fj {background-position: -80px -44px}.flag.flag-fk {background-position: -96px -44px}.flag.flag-fm {background-position: -112px -44px}.flag.flag-fo {background-position: -128px -44px}.flag.flag-fr {background-position: -144px -44px}.flag.flag-ga {background-position: -160px -44px}.flag.flag-gb {background-position: -176px -44px}.flag.flag-gd {background-position: -192px -44px}.flag.flag-ge {background-position: -208px -44px}.flag.flag-gf {background-position: -224px -44px}.flag.flag-gg {background-position: -240px -44px}.flag.flag-gh {background-position: 0 -55px}.flag.flag-gi {background-position: -16px -55px}.flag.flag-gl {background-position: -32px -55px}.flag.flag-gm {background-position: -48px -55px}.flag.flag-gn {background-position: -64px -55px}.flag.flag-gp {background-position: -80px -55px}.flag.flag-gq {background-position: -96px -55px}.flag.flag-gr {background-position: -112px -55px}.flag.flag-gs {background-position: -128px -55px}.flag.flag-gt {background-position: -144px -55px}.flag.flag-gu {background-position: -160px -55px}.flag.flag-gw {background-position: -176px -55px}.flag.flag-gy {background-position: -192px -55px}.flag.flag-hk {background-position: -208px -55px}.flag.flag-hm {background-position: -224px -55px}.flag.flag-hn {background-position: -240px -55px}.flag.flag-hr {background-position: 0 -66px}.flag.flag-ht {background-position: -16px -66px}.flag.flag-hu {background-position: -32px -66px}.flag.flag-ic {background-position: -48px -66px}.flag.flag-id {background-position: -64px -66px}.flag.flag-ie {background-position: -80px -66px}.flag.flag-il {background-position: -96px -66px}.flag.flag-im {background-position: -112px -66px}.flag.flag-in {background-position: -128px -66px}.flag.flag-io {background-position: -144px -66px}.flag.flag-iq {background-position: -160px -66px}.flag.flag-ir {background-position: -176px -66px}.flag.flag-is {background-position: -192px -66px}.flag.flag-it {background-position: -208px -66px}.flag.flag-je {background-position: -224px -66px}.flag.flag-jm {background-position: -240px -66px}.flag.flag-jo {background-position: 0 -77px}.flag.flag-jp {background-position: -16px -77px}.flag.flag-ke {background-position: -32px -77px}.flag.flag-kg {background-position: -48px -77px}.flag.flag-kh {background-position: -64px -77px}.flag.flag-ki {background-position: -80px -77px}.flag.flag-km {background-position: -96px -77px}.flag.flag-kn {background-position: -112px -77px}.flag.flag-kp {background-position: -128px -77px}.flag.flag-kr {background-position: -144px -77px}.flag.flag-kurdistan {background-position: -160px -77px}.flag.flag-kw {background-position: -176px -77px}.flag.flag-ky {background-position: -192px -77px}.flag.flag-kz {background-position: -208px -77px}.flag.flag-la {background-position: -224px -77px}.flag.flag-lb {background-position: -240px -77px}.flag.flag-lc {background-position: 0 -88px}.flag.flag-li {background-position: -16px -88px}.flag.flag-lk {background-position: -32px -88px}.flag.flag-lr {background-position: -48px -88px}.flag.flag-ls {background-position: -64px -88px}.flag.flag-lt {background-position: -80px -88px}.flag.flag-lu {background-position: -96px -88px}.flag.flag-lv {background-position: -112px -88px}.flag.flag-ly {background-position: -128px -88px}.flag.flag-ma {background-position: -144px -88px}.flag.flag-mc {background-position: -160px -88px}.flag.flag-md {background-position: -176px -88px}.flag.flag-me {background-position: -192px -88px}.flag.flag-mg {background-position: -208px -88px}.flag.flag-mh {background-position: -224px -88px}.flag.flag-mk {background-position: -240px -88px}.flag.flag-ml {background-position: 0 -99px}.flag.flag-mm {background-position: -16px -99px}.flag.flag-mn {background-position: -32px -99px}.flag.flag-mo {background-position: -48px -99px}.flag.flag-mp {background-position: -64px -99px}.flag.flag-mq {background-position: -80px -99px}.flag.flag-mr {background-position: -96px -99px}.flag.flag-ms {background-position: -112px -99px}.flag.flag-mt {background-position: -128px -99px}.flag.flag-mu {background-position: -144px -99px}.flag.flag-mv {background-position: -160px -99px}.flag.flag-mw {background-position: -176px -99px}.flag.flag-mx {background-position: -192px -99px}.flag.flag-my {background-position: -208px -99px}.flag.flag-mz {background-position: -224px -99px}.flag.flag-na {background-position: -240px -99px}.flag.flag-nc {background-position: 0 -110px}.flag.flag-ne {background-position: -16px -110px}.flag.flag-nf {background-position: -32px -110px}.flag.flag-ng {background-position: -48px -110px}.flag.flag-ni {background-position: -64px -110px}.flag.flag-nl {background-position: -80px -110px}.flag.flag-no {background-position: -96px -110px}.flag.flag-np {background-position: -112px -110px}.flag.flag-nr {background-position: -128px -110px}.flag.flag-nu {background-position: -144px -110px}.flag.flag-nz {background-position: -160px -110px}.flag.flag-om {background-position: -176px -110px}.flag.flag-pa {background-position: -192px -110px}.flag.flag-pe {background-position: -208px -110px}.flag.flag-pf {background-position: -224px -110px}.flag.flag-pg {background-position: -240px -110px}.flag.flag-ph {background-position: 0 -121px}.flag.flag-pk {background-position: -16px -121px}.flag.flag-pl {background-position: -32px -121px}.flag.flag-pm {background-position: -48px -121px}.flag.flag-pn {background-position: -64px -121px}.flag.flag-pr {background-position: -80px -121px}.flag.flag-ps {background-position: -96px -121px}.flag.flag-pt {background-position: -112px -121px} .flag.flag-pw {background-position: -128px -121px} .flag.flag-py {background-position: -144px -121px} .flag.flag-qa {background-position: -160px -121px} .flag.flag-re {background-position: -176px -121px} .flag.flag-ro {background-position: -192px -121px} .flag.flag-rs {background-position: -208px -121px} .flag.flag-ru {background-position: -224px -121px} .flag.flag-rw {background-position: -240px -121px} .flag.flag-sa {background-position: 0 -132px} .flag.flag-sb {background-position: -16px -132px} .flag.flag-sc {background-position: -32px -132px} .flag.flag-scotland {background-position: -48px -132px} .flag.flag-sd {background-position: -64px -132px} .flag.flag-se {background-position: -80px -132px} .flag.flag-sg {background-position: -96px -132px} .flag.flag-sh {background-position: -112px -132px} .flag.flag-si {background-position: -128px -132px} .flag.flag-sk {background-position: -144px -132px} .flag.flag-sl {background-position: -160px -132px} .flag.flag-sm {background-position: -176px -132px} .flag.flag-sn {background-position: -192px -132px} .flag.flag-so {background-position: -208px -132px} .flag.flag-somaliland {background-position: -224px -132px} .flag.flag-sr {background-position: -240px -132px} .flag.flag-ss {background-position: 0 -143px} .flag.flag-st {background-position: -16px -143px} .flag.flag-sv {background-position: -32px -143px} .flag.flag-sx {background-position: -48px -143px} .flag.flag-sy {background-position: -64px -143px} .flag.flag-sz {background-position: -80px -143px} .flag.flag-tc {background-position: -96px -143px} .flag.flag-td {background-position: -112px -143px} .flag.flag-tf {background-position: -128px -143px} .flag.flag-tg {background-position: -144px -143px} .flag.flag-th {background-position: -160px -143px} .flag.flag-tibet {background-position: -176px -143px} .flag.flag-tj {background-position: -192px -143px} .flag.flag-tk {background-position: -208px -143px} .flag.flag-tl {background-position: -224px -143px} .flag.flag-tm {background-position: -240px -143px} .flag.flag-tn {background-position: 0 -154px}.flag.flag-to {background-position: -16px -154px}.flag.flag-tr {background-position: -32px -154px}.flag.flag-tt {background-position: -48px -154px}.flag.flag-tv {background-position: -64px -154px}.flag.flag-tw {background-position: -80px -154px}.flag.flag-tz {background-position: -96px -154px}.flag.flag-ua {background-position: -112px -154px}.flag.flag-ug {background-position: -128px -154px}.flag.flag-um {background-position: -144px -154px}.flag.flag-us {background-position: -160px -154px}.flag.flag-uy {background-position: -176px -154px}.flag.flag-uz {background-position: -192px -154px}.flag.flag-va {background-position: -208px -154px}.flag.flag-vc {background-position: -224px -154px}.flag.flag-ve {background-position: -240px -154px}.flag.flag-vg {background-position: 0 -165px}.flag.flag-vi {background-position: -16px -165px}.flag.flag-vn {background-position: -32px -165px}.flag.flag-vu {background-position: -48px -165px}.flag.flag-wales {background-position: -64px -165px}.flag.flag-wf {background-position: -80px -165px}.flag.flag-ws {background-position: -96px -165px}.flag.flag-xk {background-position: -112px -165px}.flag.flag-ye {background-position: -128px -165px}.flag.flag-yt {background-position: -144px -165px}.flag.flag-za {background-position: -160px -165px}.flag.flag-zanzibar {background-position: -176px -165px}.flag.flag-zm {background-position: -192px -165px}.flag.flag-zw {background-position: -208px -165px}
</style>

<!-- HTML code for the LRT -->
<div class="hm-lrt">
    <div class="hm-powered">Powered by <a href="https://www.hippomundo.com" target="_blank">Hippomundo</a> &copy;</div>
    <h1 class="hm-title"><?php echo $main_title;?></h1>
    <div class="hm-subtitle"><?php echo $sub_title;?></div>
    <?php $results->display(); ?>
</div>

<?php

class HippomundoResults
{
    public $base_url = 'https://www.hippomundo.com/';
    public $rider_url;
    public $pedigree_url;
    public $studbook;
    public $api_key;
    public $days = 10;
    public $minimum_placing = 10;

    /**
     * HippomundoResults constructor.
     * @param $params
     * @throws Exception
     */
    public function __construct($params)
    {
        if(isset($params['your_key'])) {
            $this->api_key = $params['your_key'];
        } else { throw new \Exception('No API key provided'); }
        if(isset($params['studbook'])) {
            $this->studbook = $params['studbook'];
        } else { throw new \Exception('No studbook provided'); }
        if(isset($params['days'])) {
            $this->api_key = $params['days'];
        }
        if(isset($params['minimum_placing'])) {
            $this->api_key = $params['minimum_placing'];
        }
        $this->api_url = $this->base_url . 'webservice/api_results_tools/studbook/name/';
        $this->pedigree_url = $this->base_url . 'nl/horses/pedigree/line';
        $this->rider_url = $this->base_url . 'nl/competitions/rider';
    }

    public function display()
    {
        $url = $this->api_url.$this->studbook.'/apikey/'.$this->api_key.'/days/'.$this->days.'/place/'.$this->minimum_placing;

        /**
         * Do the Request to Hippomundo servers
         */
        $timeout = 10;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        $response = curl_exec($ch);
        if($response === false) { echo "No events"; }
        curl_close($ch);

        /**
         * Format and sort the results
         */
        $editions = array();
        if ($response) {$editions = $this->format_results_array(json_decode($response, true));}

        $this->formatEditions($editions);

    }

    protected function format_results_array($items) {
        $new_editions = array();
        if (!empty($items)) {
            foreach ($items as $item) {
                if (!empty($item['editions'])) {
                    foreach ($item['editions'] as $edition) {
                        $edition['competition_name'] = $item['name'];
                        $edition['discipline'] = $item['discipline'];
                        $new_editions[] = $edition;
                    }
                }
            }
            usort($new_editions, array($this, 'lrt_sort_editions'));
        }
        return $new_editions;
    }

    protected function lrt_sort_editions($a, $b) {
        $class_a = $a['classes'][0];
        $class_b = $b['classes'][0];
        $datetime1 = date_create_from_format('Y-m-d', $class_a['date']);
        $datetime2 = date_create_from_format('Y-m-d', $class_b['date']);
        if ($datetime1 === false) return 0;
        if ($datetime2 === false) return 0;
        $interval = date_diff($datetime1, $datetime2);
        $r = (int)$interval->format('%R%a');
        if ($r) {
            return $r / abs($r);
        } else {
            // comment these 2 lines lines and uncomment the 2 subsequent lines if you have problems with iconv
            $name_a = iconv('utf-8', 'ascii//TRANSLIT', $a['competition_name']);
            $name_b = iconv('utf-8', 'ascii//TRANSLIT', $b['competition_name']);
            //$name_a = $a['competition_name'];
            //$name_b = $b['competition_name'];
            if ($name_a < $name_b) return -1;
            if ($name_a > $name_b) return 1;
            return 0;
        }
    }

    protected function formatEditions($editions)
    {
        if (!empty($editions)) {
            echo '<table>';
            foreach ($editions as $edition) {
                echo $this->formatCompetition($edition);
            }
            echo '</table>';
            echo '<div style="text-align: center;"><p class="hm-powered">Powered by <a href="' . $this->base_url . 'nl" target="_blank">Hippomundo</a> &copy;</p></div>';
        } else {
            echo '<div class="alert alert-info">Geen resultaten</div>';
        }
    }

    protected function formatCompetition($edition)
    {
        $return = '<tr class="hm-head"><td>';
        $return .= $edition["competition_name"] . ' ' . $edition["name"] . ' ' . $edition["discipline"];
        $return .= '</td></tr>';
        if (!empty($edition["classes"])) {
            foreach ($edition["classes"] as $class) {
                $return .= $this->formatClass($class);
            }
        }
        return $return;
    }

    protected function formatClass($class)
    {
        $return = '<tr class="hm-subhead"><td>';
        $return .= '<span class="date">' . $class["date"] . '</span> - ' . $class["name"] . ' : ' . $class['horses'][0]['sort'] . ' ';
        $return .= ($class["height"] && $class["height"] != 0 ? '(' . $class["height"] . ')' : '');
        $return .= '</td></tr>';
        if (!empty($class["horses"])) {
            $return .= '<tr><td><table><colgroup class="hm-columns"><col/><col/><col/><col/><col/></colgroup>';
            foreach ($class["horses"] as $key => $result) {
                $return .= $this->formatResult($result);
            }
            $return .= '</table></td></tr>';
        }
        return $return;
    }

    protected function formatResult($result)
    {
        $return = '<tr>';
        $return .= '<td class="rank">' . $this->formatRank($result) . '</td>';
        $return .= '<td>' . $this->formatHorse($result, $this->pedigree_url) . $this->formatBreeder($result, $this->base_url) . '</td>';
        $return .= '<td>' . $this->formatRider($result, $this->rider_url) . '</td>';
        $return .= '<td class="hm-small\">' . $this->formatFlag($result, $this->base_url) . '</td>';
        $return .= '<td class="hm-prize\">' . $this->formatPrize($result) . '</td>';
        $return .= '</tr>';
        return $return;
    }


    protected function formatRider($result, $rider_url)
    {
        return '<a href="' . $rider_url . '/' . $result['rider_id'] . '" target="_blank">' . $result['rider'] . '</a>';
    }

    protected function formatRank($result)
    {
        return (($result['place'] >= 900 && $result['place'] <= 999) ? '-' : $result['place']);
    }

    protected function formatFlag($result, $base_url)
    {
        $return = '';
        if (!empty($result['iso_code'])) {
            $return .= '<img src="' . $base_url . 'images/blank.gif" class="flag flag-' . strtolower($result['iso_code']) . '" title="' . $result['country'] . '">';
        }
        return $return;
    }

    protected function formatBreeder($result, $base_url)
    {
        $breeder = '';
        if (strlen($result['breeder']) > 1) {
            $breeder .= '<br><span class="fokker-link">Fokker: ';
            if ($result['breeder_published'] !== '0' && strlen($result['breeder_website']) > 1) {
                $breeder .= '<a target="_blank" href="' . $result['breeder_website'] . '">' . $result['breeder'] . '</a>';
            } else {
                $breeder .= $result['breeder'] . '&nbsp;<a target="_blank" href="' . $base_url . '/breeder-link">';
                $breeder .= '<img src="' . $base_url . 'images/info-icon-grey.png" alt="Advertise your link here"></a>';
            }
            $breeder .= '</span>';
        }
        return $breeder;
    }

    protected function formatHorse($result, $pedigree_url)
    {
        $horse = '<a href="' . $pedigree_url . '/' . $result['horse_id'] . '" target="_blank">' . $result['horse_name'] . '</a>';
        $horse .= '&nbsp;<span class="sire">(<a href="' . $pedigree_url . '/' . $result['father_id'] . '" target="_blank" class="hm-sire">' . $result['father_name'] . '</a>)</span>';
        return $horse;
    }

    protected function formatPrize($result)
    {
        $output = '';
        if (isset($result['a_fout']) && $result['a_fout'] != '') {
            $output .= $result['a_fout'] . '/';
        }
        if (isset($result['a_tijd']) && $result['a_tijd'] != '') {
            $output .= $result['a_tijd'] . '/';
        }
        if (isset($result['b_fout']) && $result['b_fout'] != '') {
            $output .= $result['b_fout'] . '/';
        }
        if (isset($result['b_tijd']) && $result['b_tijd'] != '') {
            $output .= $result['b_tijd'] . '/';
        }
        if (isset($result['one_fout']) && $result['one_fout'] != '') {
            $output .= $result['one_fout'] . '/';
        }
        if (isset($result['one_tijd']) && $result['one_tijd'] != '') {
            $output .= $result['one_tijd'] . '/';
        }
        $output = substr($output, 0, -1);
        if (strlen($output) >= 2) {
            $output .= '<br>';
        }
        $output .= ($result['prize'] == '0.00' || $result['prize'] == 0) ? '' : '&euro; ' . number_format($result['prize'],
                $decimals = 0, $dec_point = ",", $thousands_sep = ".");
        return $output;
    }
}
