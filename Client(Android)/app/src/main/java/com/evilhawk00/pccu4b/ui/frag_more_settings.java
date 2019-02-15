package com.evilhawk00.pccu4b.ui;

import android.annotation.SuppressLint;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;
import android.view.View.OnClickListener;
import android.widget.Toast;

import com.evilhawk00.pccu4b.LoginActivity;
import com.evilhawk00.pccu4b.ObscuredSharedPreferences;
import com.evilhawk00.pccu4b.R;
import com.evilhawk00.pccu4b.SharedDataBetweenActivities;


import java.io.IOException;

import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;


@SuppressLint("ValidFragment")
public class frag_more_settings extends Fragment{

    private String mTitle;
    private static final int CONNECTION_TIMEOUT = 10000;
    private static final int READ_TIMEOUT = 15000;

    private static final String HostName = SharedDataBetweenActivities.GetHostName();

    public static frag_more_settings getInstance(String title) {
        frag_more_settings sf = new frag_more_settings();
        sf.mTitle = title;
        return sf;
    }


    class AsyncLogin extends AsyncTask<Void, Void, Boolean> {
        ProgressDialog pdLoading = new ProgressDialog(getActivity());
        HttpURLConnection conn;
        URL url = null;
        ObscuredSharedPreferences prefs = ObscuredSharedPreferences.getPrefs(requireActivity(), "PCCU4BDataStore", Context.MODE_PRIVATE);

        @Override
        protected void onPreExecute() {
            super.onPreExecute();

            //this method will be running on UI thread
            pdLoading.setMessage(requireActivity().getResources().getString(R.string.ProgressDialog_Label_Loading));
            pdLoading.setCancelable(false);
            pdLoading.show();

        }

        @Override
        protected Boolean doInBackground(Void... params) {



            String STOREDTOKEN = prefs.getString("AuthenticationToken", null);


            try {


                url = new URL("http://"+ HostName +"/MobileAppAPI/AppLogout.php");




            } catch (MalformedURLException e) {
                e.printStackTrace();
                return false;

            }
            try {


                conn = (HttpURLConnection) url.openConnection();
                conn.setReadTimeout(READ_TIMEOUT);
                conn.setConnectTimeout(CONNECTION_TIMEOUT);
                conn.setRequestMethod("GET");
                conn.setRequestProperty("AppToken", STOREDTOKEN);

                // setDoOutput to true as we recieve data from json file
                conn.setDoInput(true);


            } catch (IOException e1) {
                e1.printStackTrace();
                return false;

            }

            try {

                int response_code = conn.getResponseCode();


                if (response_code == HttpURLConnection.HTTP_OK) {

                    return true;

                } else {

                    return false;
                }

            } catch (IOException e) {
                e.printStackTrace();

                return false;
            } finally {
                conn.disconnect();
            }


        }

        @Override
        protected void onPostExecute(Boolean result) {

            //this method will be running on UI thread
            if (result){
                pdLoading.dismiss();
                prefs.edit().remove("Login").commit();
                prefs.edit().remove("pwd").commit();
                prefs.edit().remove("AuthenticationToken").commit();


                Intent myIntent = new Intent(requireActivity(), LoginActivity.class);
                startActivity(myIntent);

                requireActivity().finish();

            }else{

                pdLoading.dismiss();
                Toast.makeText(requireActivity(), requireActivity().getResources().getString(R.string.Toast_Label_LogoutFailed), Toast.LENGTH_LONG).show();
            }


        }


    }



    @Override
    public void onCreate(Bundle savedInstanceState) {


        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View v = inflater.inflate(R.layout.frag_more_settings, container, false);
        ObscuredSharedPreferences prefs = ObscuredSharedPreferences.getPrefs(requireActivity(), "PCCU4BDataStore", Context.MODE_PRIVATE);
        String STOREDUSERNAME = prefs.getString("CurrentUserName", null);
        TextView ShowCurrentUser;
        Button buttonLogout;
        ShowCurrentUser = v.findViewById(R.id.textShowUsername);
        ShowCurrentUser.setText(container.getResources().getString(R.string.Frag_Overview_Label_WelcomeUser,STOREDUSERNAME));


        buttonLogout = v.findViewById(R.id.btnLogout);


        buttonLogout.setOnClickListener(new OnClickListener() { // calling onClick() method
            @Override
            public void onClick(View v) {

                new AsyncLogin().execute();


            }
        });

        return v;
    }
}
