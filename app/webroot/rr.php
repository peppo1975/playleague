<?
function generateRoundRobinPairings($num_players) {
    //do we have a positive number of players? otherwise default to 4
    $num_players = ($num_players > 0) ? (int)$num_players : 4;
    //set number of players to even number
    $num_players = ($num_players % 2 == 0) ? $num_players : $num_players + 1;
    //format for pretty alignment of pairings across rounds
    $format = "%0" . ceil(log10($num_players)) . "d";
    $pairing = "$format-$format ";
    //set the return value
    $ret = $num_players . " Player Round Robin:\n-----------------------";
    //print the rounds
    for ($round = 1; $round < $num_players; $round++) {
        $ret .= sprintf("\nRound #$format : ", $round);
        $players_done = array();
        //print the pairings
        for ($player = 1; $player < $num_players; $player++) {
            if (!in_array($player, $players_done)) {
                //select opponent
                $opponent = $round - $player;
                $opponent += ($opponent < 0) ? $num_players : 1;
                //ensure opponent is not the current player
                if ($opponent != $player) {
                    //choose colours
                    if ($player % 2 == $opponent % 2) {
                        if ($player < $opponent) {
                            //player plays black
                            $ret .= sprintf($pairing, $opponent, $player);
                        } else {
                            //player plays white
                            $ret .= sprintf($pairing, $player, $opponent);
                        }
                    } else {
                        if ($player < $opponent) {
                            //player plays white
                            $ret .= sprintf($pairing, $player, $opponent);
                        } else {
                            //player plays black
                            $ret .= sprintf($pairing, $opponent, $player);
                        }
                    }
                    //these players are done for this round
                    $players_done[] = $player;
                    $players_done[] = $opponent;
                }
            }
        }
        //print the last pairing (i.e. for the last player)
        if ($round % 2 == 0) {
            $opponent = ($round + $num_players) / 2;
            //last player plays white
            $ret .= sprintf($pairing, $num_players, $opponent);
        } else {
            $opponent = ($round + 1) / 2;
            //last player plays black
            $ret .= sprintf($pairing, $opponent, $num_players);
        }
    }
    return $ret;
} 

echo generateRoundRobinPairings(8);

?>
