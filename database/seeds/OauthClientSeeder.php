<?php


use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\OAuth\OAuthClient;
use App\Models\OAuth\OAuthClientScope;
//use DB;

class OauthClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {
        $oauth_client_scope_arr = [
            [
                "client_name"=> 'api',
                "scopes"=>[
                    [
                        "name"=>'SuperUser',
                    ],
                    [
                        "name"=>'User',
                    ]
                    
                    
                ]
            ]
        ];

        $faker = Faker::create();

        // Seed OAuth Client And Client Scope
        foreach ($oauth_client_scope_arr as $oauth_client) {
            $exists = DB::table('oauth_clients')->where([
                'name' => $oauth_client["client_name"],
            ])->first();
             //dd($exists);
            if (!$exists) {
                $clientId = $this->generatePin(16);
                RECHECK_CLIENT:
                if (OAuthClient::where('id', '=', $clientId)->exists()) {
                    $clientId = $this->generatePin(16);
                    goto RECHECK_CLIENT;
                } else {
                    $clientSecret = $this->generatePin(32);

                    RECHECK_SECRET:
                    if (OAuthClient::where('secret', '=', $clientSecret)->exists()) {
                        $clientSecret = $this->generatePin(32);
                        goto RECHECK_SECRET;
                    }

                    $oauthClient = OAuthClient::create([
                        'id' => $clientId,
                        'name' => $oauth_client["client_name"],
                        'secret' => $clientSecret,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);


                    // Seed Client Scope
                    foreach ($oauth_client["scopes"] as $oauth_client_scope) {
                        if (!(OAuthClientScope::where('id', '=', $oauth_client_scope["name"])->exists())) {
                            $oauthClientScope = OAuthClientScope::create([
                                'id' => $oauth_client_scope["name"],
                                'description' => $oauth_client_scope["name"],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ]);

                            // Seed Client Id And Scope Id in bridge table
                            DB::table('oauth_client_scopes')->insert([
                                'client_id' => $clientId,
                                'scope_id' => $oauth_client_scope["name"],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ]);
                        }
                    }
                }
            }
            else
            {
                foreach ($oauth_client["scopes"] as $oauth_client_scope) {
                    if (!(OAuthClientScope::where('id', '=', $oauth_client_scope["name"])->exists())) {
                        $oauthClientScope = OAuthClientScope::create([
                            'id' => $oauth_client_scope["name"],
                            'description' => $oauth_client_scope["name"],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);

                        // Seed Client Id And Scope Id in bridge table
                        DB::table('oauth_client_scopes')->insert([
                            'client_id' => $exists->id,
                            'scope_id' => $oauth_client_scope["name"],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                    }
                }

            }
        }
      }


    public function generatePin( $number ) {
        // Generate set of alpha characters
        $alpha = array();
        for ($u = 65; $u <= 90; $u++) {
            // Uppercase Char
            array_push($alpha, chr($u));
        }

        // Just in case you need lower case
        for ($l = 97; $l <= 122; $l++) {
            // Lowercase Char
            array_push($alpha, chr($l));
        }

        // Get random alpha character
        $rand_alpha_key = array_rand($alpha);
        $rand_alpha = $alpha[$rand_alpha_key];

        // Add the other missing integers
        $rand = array($rand_alpha);
        for ($c = 0; $c < $number - 1; $c++) {
            array_push($rand, mt_rand(0, 9));
            shuffle($rand);
        }

        return implode('', $rand);
    }
}
