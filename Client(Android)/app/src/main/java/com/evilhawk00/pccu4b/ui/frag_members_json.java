package com.evilhawk00.pccu4b.ui;

import android.annotation.SuppressLint;
import android.app.ProgressDialog;
import android.content.Context;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;

import com.evilhawk00.pccu4b.ObscuredSharedPreferences;
import com.evilhawk00.pccu4b.R;
import com.evilhawk00.pccu4b.SharedDataBetweenActivities;

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
import java.util.ArrayList;
import java.util.List;

@SuppressLint("ValidFragment")
public class frag_members_json extends Fragment {

    private String mTitle;
    private static final int CONNECTION_TIMEOUT = 10000;
    private static final int READ_TIMEOUT = 15000;
    private RecyclerView RVmemberlist;
    private MemberListAdapter mAdapter;


    private static final String HostName = SharedDataBetweenActivities.GetHostName();

    public static frag_members_json getInstance(String title) {
        frag_members_json sf = new frag_members_json();
        sf.mTitle = title;
        return sf;
    }

    class AsyncLogin extends AsyncTask<String, String, String> {
        ProgressDialog pdLoading = new ProgressDialog(getActivity());
        HttpURLConnection conn;
        URL url = null;

        @Override
        protected void onPreExecute() {
            super.onPreExecute();

            //this method will be running on UI thread
            pdLoading.setMessage(requireActivity().getResources().getString(R.string.ProgressDialog_Label_Loading));
            pdLoading.setCancelable(false);
            pdLoading.show();

        }

        @Override
        protected String doInBackground(String... params) {

            ObscuredSharedPreferences prefs = ObscuredSharedPreferences.getPrefs(requireActivity(), "PCCU4BDataStore", Context.MODE_PRIVATE);
            String STOREDTOKEN = prefs.getString("AuthenticationToken", null);


            try {

                url = new URL("http://"+ HostName +"/MobileAppAPI/Members_json.php");




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


                if (response_code == HttpURLConnection.HTTP_OK) {


                    InputStream input = conn.getInputStream();
                    BufferedReader reader = new BufferedReader(new InputStreamReader(input));
                    StringBuilder result = new StringBuilder();
                    String line;

                    while ((line = reader.readLine()) != null) {
                        result.append(line);
                    }

                    // Pass data to onPostExecute method
                    return (result.toString());

                } else {

                    return ("unsuccessful");
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


            List<MemberData> data=new ArrayList<>();
            pdLoading.dismiss();

            try {

                JSONArray jArray = new JSONArray(result);

                // Extract data from json and store into ArrayList as class objects
                for(int i=0;i<jArray.length();i++){
                    JSONObject json_data = jArray.getJSONObject(i);
                    MemberData memberlist = new MemberData();
                    memberlist.DISPLAYNAME= json_data.getString("DisplayName");
                    memberlist.NICKNAME= json_data.getString("Nickname");
                    memberlist.TOTALWORKTIME= json_data.getString("Total");
                    memberlist.LASTLOGIN= json_data.getString("LastLoginTime");
                    data.add(memberlist);
                }





            } catch (JSONException e) {
                Toast.makeText(getActivity(), e.toString(), Toast.LENGTH_LONG).show();
            }
            mAdapter = new MemberListAdapter(getActivity(), data);

            RVmemberlist.setAdapter(mAdapter);
            RVmemberlist.setLayoutManager(new LinearLayoutManager(getActivity()));
        }


    }



    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);



    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View v = inflater.inflate(R.layout.frag_memberlist, container, false);

        new AsyncLogin().execute();

        RVmemberlist = v.findViewById(R.id.MemberList);
        RVmemberlist.setAdapter(mAdapter);
        RVmemberlist.setLayoutManager(new LinearLayoutManager(getActivity()));

        return v;
    }
}