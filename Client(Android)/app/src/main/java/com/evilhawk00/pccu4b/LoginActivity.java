package com.evilhawk00.pccu4b;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.AsyncTask;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;



import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.evilhawk00.pccu4b.ui.MainActivity;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLEncoder;
import java.nio.charset.StandardCharsets;
import java.util.LinkedHashMap;
import java.util.Map;

import static com.evilhawk00.pccu4b.R.id.InputPassword;

public class LoginActivity extends AppCompatActivity implements View.OnClickListener{


    private static final int CONNECTION_TIMEOUT = 10000;
    private static final int READ_TIMEOUT = 15000;
    public static final String KEY_USERNAME = "username";
    public static final String KEY_PASSWORD = "password";

    private EditText editTextUsername;
    private EditText editTextPassword;
    private Button buttonLogin;



    private static final String HostName = SharedDataBetweenActivities.GetHostName();




    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        ObscuredSharedPreferences prefs = ObscuredSharedPreferences.getPrefs(LoginActivity.this, "PCCU4BDataStore", Context.MODE_PRIVATE);
        String LASTLOGINDATA=prefs.getString("Login", null);
        if(LASTLOGINDATA!=null){
            setContentView(R.layout.activity_autologin);
            new ValidateExistingToken().execute();
        }
        else {

            setContentView(R.layout.activity_login);

            editTextUsername = findViewById(R.id.InputEmail);
            editTextPassword = findViewById(InputPassword);

            buttonLogin = findViewById(R.id.btnLogin);

            buttonLogin.setOnClickListener(this);
        }

        //Make call to AsyncTask

    }

    public void onClick(View v) {

        new AsyncLogin().execute();
    }




    private class ValidateExistingToken extends AsyncTask<String, String, String> {

        HttpURLConnection conn;
        URL url = null;
        ObscuredSharedPreferences prefs = ObscuredSharedPreferences.getPrefs(LoginActivity.this, "PCCU4BDataStore", Context.MODE_PRIVATE);
        String STOREDTOKEN = prefs.getString("AuthenticationToken", null);



        @Override
        protected void onPreExecute() {
            super.onPreExecute();



        }

        @Override
        protected String doInBackground(String... args) {
            try {


                url = new URL("http://"+ HostName +"/MobileAppAPI/ValidateToken.php");




            } catch (MalformedURLException e) {

                e.printStackTrace();
                return e.toString();
            }



            try {



                // Setup HttpURLConnection class to send and receive data from php and mysql
                conn = (HttpURLConnection) url.openConnection();
                conn.setReadTimeout(READ_TIMEOUT);
                conn.setConnectTimeout(CONNECTION_TIMEOUT);
                conn.setRequestMethod("GET");
                conn.setRequestProperty("AppToken", STOREDTOKEN);

                // setDoOutput to true as we recieve data from json file
                conn.setDoInput(true);



            } catch (IOException e1) {

                e1.printStackTrace();
                return e1.toString();
            }

            try {

                int response_code = conn.getResponseCode();

                // Check if successful connection made
                if (response_code == HttpURLConnection.HTTP_OK) {

                    // Read data sent from server
                    InputStream input = conn.getInputStream();
                    BufferedReader reader = new BufferedReader(new InputStreamReader(input));
                    StringBuilder result = new StringBuilder();
                    String line;

                    while ((line = reader.readLine()) != null) {
                        result.append(line);
                    }


                    return (result.toString());

                } else {

                    return ("Server responded an Error CODE");
                }

            } catch (IOException e) {
                e.printStackTrace();
                return e.toString();
            } finally {
                conn.disconnect();
            }


        }

        @Override
        protected void onPostExecute(String result) {



            try {

                JSONArray jArray = new JSONArray(result);
                JSONObject json_data = jArray.getJSONObject(0);

                String TOKENVALIDATION = json_data.getString("Token");



                if (TOKENVALIDATION.equals("valid")){




                    Intent myIntent = new Intent(LoginActivity.this, MainActivity.class);
                    startActivity(myIntent);
                    //close this activity
                    finish();
                }
                else{
                    //Session expired,relogin
                    new AutoReLogin().execute();
                }




            } catch (JSONException e) {
                Toast.makeText(LoginActivity.this, e.toString(), Toast.LENGTH_LONG).show();

                AlertDialog.Builder builder = new AlertDialog.Builder(LoginActivity.this);
                builder.setMessage(LoginActivity.this.getResources().getString(R.string.Dialog_Label_NetError))
                        .setCancelable(false)
                        .setPositiveButton(LoginActivity.this.getResources().getString(R.string.Dialog_Label_OK), new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {
                                //do things
                            }
                        });
                AlertDialog alert = builder.create();

                setContentView(R.layout.activity_login);

                editTextUsername = findViewById(R.id.InputEmail);
                editTextPassword = findViewById(InputPassword);

                buttonLogin = findViewById(R.id.btnLogin);

                buttonLogin.setOnClickListener(LoginActivity.this);
                alert.show();

            }

        }

    }


    private class AutoReLogin extends AsyncTask<String, String, String> {

        HttpURLConnection conn;
        URL url = null;
        ObscuredSharedPreferences prefs = ObscuredSharedPreferences.getPrefs(LoginActivity.this, "PCCU4BDataStore", Context.MODE_PRIVATE);
        String username = prefs.getString("Login", null);
        String password = prefs.getString("pwd", null);


        @Override
        protected void onPreExecute() {
            super.onPreExecute();



        }

        @Override
        protected String doInBackground(String... args) {
            try {


                url = new URL("http://"+ HostName +"/MobileAppAPI/AppLogin.php");




            } catch (MalformedURLException e) {

                e.printStackTrace();
                return e.toString();
            }



            try {

                Map<String,Object> params = new LinkedHashMap<>();
                params.put(KEY_USERNAME, username);
                params.put(KEY_PASSWORD, password);


                StringBuilder postData = new StringBuilder();
                for (Map.Entry<String,Object> param : params.entrySet()) {
                    if (postData.length() != 0) postData.append('&');
                    postData.append(URLEncoder.encode(param.getKey(), "UTF-8"));
                    postData.append('=');
                    postData.append(URLEncoder.encode(String.valueOf(param.getValue()), "UTF-8"));
                }
                byte[] postDataBytes = postData.toString().getBytes(StandardCharsets.UTF_8);






                // Setup HttpURLConnection class to send and receive data from php and mysql
                conn = (HttpURLConnection) url.openConnection();
                conn.setReadTimeout(READ_TIMEOUT);
                conn.setConnectTimeout(CONNECTION_TIMEOUT);
                conn.setRequestMethod("POST");
                conn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded;  charset=UTF-8");
                conn.setRequestProperty("Content-Length", String.valueOf(postDataBytes.length));

                // setDoOutput to true as we recieve data from json file
                conn.setDoInput(true);
                conn.setDoOutput(true);
                conn.setUseCaches(false);
                conn.getOutputStream().write(postDataBytes);


            } catch (IOException e1) {

                e1.printStackTrace();
                return e1.toString();
            }

            try {

                int response_code = conn.getResponseCode();

                // Check if successful connection made
                if (response_code == HttpURLConnection.HTTP_OK) {

                    // Read data sent from server
                    InputStream input = conn.getInputStream();
                    BufferedReader reader = new BufferedReader(new InputStreamReader(input));
                    StringBuilder result = new StringBuilder();
                    String line;

                    while ((line = reader.readLine()) != null) {
                        result.append(line);
                    }


                    return (result.toString());

                } else {

                    return ("Server responded an Error CODE");
                }

            } catch (IOException e) {
                e.printStackTrace();
                return e.toString();
            } finally {
                conn.disconnect();
            }


        }

        @Override
        protected void onPostExecute(String result) {



            try {

                JSONArray jArray = new JSONArray(result);
                JSONObject json_data = jArray.getJSONObject(0);
                //LoginData loginexecute = new LoginData();
                String LOGINRESULT = json_data.getString("Login");
                String USERDISPLAYNAME = json_data.getString("DisplayName");
                String SESSIONTOKEN = json_data.getString("AppToken");


                if (LOGINRESULT.equals("success")){

                    ObscuredSharedPreferences prefs = ObscuredSharedPreferences.getPrefs(LoginActivity.this, "PCCU4BDataStore", Context.MODE_PRIVATE);
                    prefs.edit().putString("AuthenticationToken", SESSIONTOKEN).commit();
                    prefs.edit().putString("CurrentUserName", USERDISPLAYNAME).commit();

                    Intent myIntent = new Intent(LoginActivity.this, MainActivity.class);
                    startActivity(myIntent);
                    //close this activity
                    finish();
                }
                else{

                    AlertDialog.Builder builder = new AlertDialog.Builder(LoginActivity.this);
                    builder.setMessage(LoginActivity.this.getResources().getString(R.string.Dialog_Label_NetError))
                            .setCancelable(false)
                            .setPositiveButton(LoginActivity.this.getResources().getString(R.string.Dialog_Label_OK), new DialogInterface.OnClickListener() {
                                public void onClick(DialogInterface dialog, int id) {

                                }
                            });
                    AlertDialog alert = builder.create();
                    setContentView(R.layout.activity_login);

                    editTextUsername = findViewById(R.id.InputEmail);
                    editTextPassword = findViewById(InputPassword);

                    buttonLogin = findViewById(R.id.btnLogin);

                    buttonLogin.setOnClickListener(LoginActivity.this);
                    alert.show();
                }




            } catch (JSONException e) {
                Toast.makeText(LoginActivity.this, e.toString(), Toast.LENGTH_LONG).show();

                AlertDialog.Builder builder = new AlertDialog.Builder(LoginActivity.this);
                builder.setMessage(LoginActivity.this.getResources().getString(R.string.Dialog_Label_NetError))
                        .setCancelable(false)
                        .setPositiveButton(LoginActivity.this.getResources().getString(R.string.Dialog_Label_OK), new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {
                                //do things
                            }
                        });
                AlertDialog alert = builder.create();
                alert.show();

            }

        }

    }








    private class AsyncLogin extends AsyncTask<String, String, String> {
        ProgressDialog pdLoading = new ProgressDialog(LoginActivity.this);
        HttpURLConnection conn;
        URL url = null;
        String username = editTextUsername.getText().toString().trim();
        String password = editTextPassword.getText().toString().trim();


        @Override
        protected void onPreExecute() {
            super.onPreExecute();

            //this method will be running on UI thread
            pdLoading.setMessage(LoginActivity.this.getResources().getString(R.string.ProgressDialog_Label_Loading));
            pdLoading.setCancelable(false);
            pdLoading.show();

        }

        @Override
        protected String doInBackground(String... args) {
            try {


                url = new URL("http://"+ HostName +"/MobileAppAPI/AppLogin.php");




            } catch (MalformedURLException e) {

                e.printStackTrace();
                return e.toString();
            }



            try {

                Map<String,Object> params = new LinkedHashMap<>();
                params.put(KEY_USERNAME, username);
                params.put(KEY_PASSWORD, password);


                StringBuilder postData = new StringBuilder();
                for (Map.Entry<String,Object> param : params.entrySet()) {
                    if (postData.length() != 0) postData.append('&');
                    postData.append(URLEncoder.encode(param.getKey(), "UTF-8"));
                    postData.append('=');
                    postData.append(URLEncoder.encode(String.valueOf(param.getValue()), "UTF-8"));
                }
                byte[] postDataBytes = postData.toString().getBytes(StandardCharsets.UTF_8);






                // Setup HttpURLConnection class to send and receive data from php and mysql
                conn = (HttpURLConnection) url.openConnection();
                conn.setReadTimeout(READ_TIMEOUT);
                conn.setConnectTimeout(CONNECTION_TIMEOUT);
                conn.setRequestMethod("POST");
                conn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded;  charset=UTF-8");
                conn.setRequestProperty("Content-Length", String.valueOf(postDataBytes.length));


                conn.setDoInput(true);
                conn.setDoOutput(true);
                conn.setUseCaches(false);
                conn.getOutputStream().write(postDataBytes);


            } catch (IOException e1) {

                e1.printStackTrace();
                return e1.toString();
            }

            try {

                int response_code = conn.getResponseCode();

                // Check if successful connection made
                if (response_code == HttpURLConnection.HTTP_OK) {

                    // Read data sent from server
                    InputStream input = conn.getInputStream();
                    BufferedReader reader = new BufferedReader(new InputStreamReader(input));
                    StringBuilder result = new StringBuilder();
                    String line;

                    while ((line = reader.readLine()) != null) {
                        result.append(line);
                    }


                    return (result.toString());

                } else {

                    return ("Server responded an Error CODE");
                }

            } catch (IOException e) {
                e.printStackTrace();
                return e.toString();
            } finally {
                conn.disconnect();
            }


        }

        @Override
        protected void onPostExecute(String result) {



            pdLoading.dismiss();

            try {

                JSONArray jArray = new JSONArray(result);
                JSONObject json_data = jArray.getJSONObject(0);

                String LOGINRESULT = json_data.getString("Login");
                String USERDISPLAYNAME = json_data.getString("DisplayName");
                String SESSIONTOKEN = json_data.getString("AppToken");


                if (LOGINRESULT.equals("success")){
                    ObscuredSharedPreferences prefs = ObscuredSharedPreferences.getPrefs(LoginActivity.this, "PCCU4BDataStore", Context.MODE_PRIVATE);
                    prefs.edit().putString("AuthenticationToken", SESSIONTOKEN).commit();
                    prefs.edit().putString("CurrentUserName", USERDISPLAYNAME).commit();
                    prefs.edit().putString("Login", username).commit();
                    prefs.edit().putString("pwd", password).commit();


                    Intent myIntent = new Intent(LoginActivity.this, MainActivity.class);
                    startActivity(myIntent);

                    finish();
                }
                else{

                    AlertDialog.Builder builder = new AlertDialog.Builder(LoginActivity.this);
                    builder.setMessage(LoginActivity.this.getResources().getString(R.string.Dialog_Label_WrongLogin))
                            .setCancelable(false)
                            .setPositiveButton(LoginActivity.this.getResources().getString(R.string.Dialog_Label_OK), new DialogInterface.OnClickListener() {
                                public void onClick(DialogInterface dialog, int id) {

                                }
                            });
                    AlertDialog alert = builder.create();
                    alert.show();
                }




            } catch (JSONException e) {
                Toast.makeText(LoginActivity.this, e.toString(), Toast.LENGTH_LONG).show();

                AlertDialog.Builder builder = new AlertDialog.Builder(LoginActivity.this);
                builder.setMessage(LoginActivity.this.getResources().getString(R.string.Dialog_Label_NetError))
                        .setCancelable(false)
                        .setPositiveButton(LoginActivity.this.getResources().getString(R.string.Dialog_Label_OK), new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {
                                //do things
                            }
                        });
                AlertDialog alert = builder.create();
                alert.show();

            }

        }

    }


    @Override
    public void onBackPressed() {
            AlertDialog.Builder builder = new AlertDialog.Builder(this);
            builder.setTitle(this.getResources().getString(R.string.Dialog_Label_ExitHeader));
            builder.setMessage(this.getResources().getString(R.string.Dialog_Label_ExitContent));

            builder.setPositiveButton(this.getResources().getString(R.string.Dialog_Label_Yes), new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialog, int which) {
                    dialog.dismiss();
                    finish();
                }
            });

            builder.setNegativeButton(this.getResources().getString(R.string.Dialog_Label_Cancel), new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    dialog.dismiss();
                }
            });
            AlertDialog alert = builder.create();
            alert.show();

    }

}