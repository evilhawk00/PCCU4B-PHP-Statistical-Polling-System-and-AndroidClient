package com.evilhawk00.pccu4b;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;



public class SplashScreen extends Activity {



    @Override
    public void onBackPressed() {
        //disable back button
    }



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        // Splash screen timer
        int SPLASH_TIME_OUT = 2500;
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash);

        new Handler().postDelayed(new Runnable() {

            /*
             * Showing splash screen with a timer. This will be useful when you
             * want to show case your app logo / company
             */

            @Override
            public void run() {

                Intent i = new Intent(SplashScreen.this, LoginActivity.class);
                startActivity(i);


                finish();
            }
        }, SPLASH_TIME_OUT);
    }

}