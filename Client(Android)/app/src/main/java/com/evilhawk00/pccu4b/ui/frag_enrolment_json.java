package com.evilhawk00.pccu4b.ui;

import android.annotation.SuppressLint;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
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
import java.net.URLEncoder;
import java.nio.charset.StandardCharsets;
import java.util.ArrayList;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

@SuppressLint("ValidFragment")
public class frag_enrolment_json extends Fragment {

    private String mTitle;
    private static final int CONNECTION_TIMEOUT = 10000;
    private static final int READ_TIMEOUT = 15000;
    public static final String KEY_JOBDETAIL = "WorkDetails";
    public static final String KEY_JOBREMARKS = "AdditionalDetails";
    public static final String KEY_JOBWORKINGTIME = "WorkTime";
    public static final String KEY_JOBDATE = "JobDate";
    private RecyclerView RVMyjoblist;
    private MyJobHistoryAdapter mjhAdapter;

    private EditText editJobDetail;
    private EditText editJobRemarks;
    private EditText editJobTime;
    private EditText editJobDate;


    private static final String HostName = SharedDataBetweenActivities.GetHostName();


    public static frag_enrolment_json getInstance(String title) {
        frag_enrolment_json sf = new frag_enrolment_json();
        sf.mTitle = title;
        return sf;
    }


    private class FetchMyJobHistory extends AsyncTask<String, String, String> {
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


                url = new URL("http://"+ HostName +"/MobileAppAPI/enrolment_json.php");

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




            List<MyJobHistoryData> data=new ArrayList<>();

            pdLoading.dismiss();
            try {

                JSONArray jArray = new JSONArray(result);


                for(int i=0;i<jArray.length();i++){
                    JSONObject json_data = jArray.getJSONObject(i);
                    MyJobHistoryData joblist = new MyJobHistoryData();
                    joblist.JOBDETAIL = json_data.getString("JobDetail");
                    joblist.JOBTIME = json_data.getString("Minutes");
                    joblist.JOBDATE = json_data.getString("JobDate");
                    data.add(joblist);
                }

                // Setup and Handover data to recyclerview

                mjhAdapter = new MyJobHistoryAdapter(getActivity(), data);
                RVMyjoblist.setAdapter(mjhAdapter);
                RVMyjoblist.setLayoutManager(new LinearLayoutManager(getActivity()));

            } catch (JSONException e) {
                Toast.makeText(getActivity(), e.toString(), Toast.LENGTH_LONG).show();
            }

        }

    }


    private class PostJobDetail extends AsyncTask<Void, Void, Boolean> {
        ProgressDialog pdLoading = new ProgressDialog(getActivity());
        HttpURLConnection conn;
        URL url = null;
        String sJobDetail = editJobDetail.getText().toString().trim();
        String sJobRemarks = editJobRemarks.getText().toString().trim();
        String sJobWorkingTime = editJobTime.getText().toString().trim();
        String sJobDate = editJobDate.getText().toString().trim();

        @Override
        protected void onPreExecute() {
            super.onPreExecute();

            //this method will be running on UI thread
            pdLoading.setMessage(requireActivity().getResources().getString(R.string.ProgressDialog_Label_Posting));
            pdLoading.setCancelable(false);
            pdLoading.show();

        }

        @Override
        protected Boolean doInBackground(Void... args) {

            ObscuredSharedPreferences prefs = ObscuredSharedPreferences.getPrefs(requireActivity(), "PCCU4BDataStore", Context.MODE_PRIVATE);
            String STOREDTOKEN = prefs.getString("AuthenticationToken", null);
            try {


                url = new URL("http://"+ HostName +"/MobileAppAPI/enrolment_json.php");




            } catch (MalformedURLException e) {

                e.printStackTrace();

                return false;
            }



            try {

                Map<String,Object> params = new LinkedHashMap<>();
                params.put(KEY_JOBDETAIL, sJobDetail);
                params.put(KEY_JOBREMARKS, sJobRemarks);
                params.put(KEY_JOBWORKINGTIME, sJobWorkingTime);
                params.put(KEY_JOBDATE, sJobDate);


                StringBuilder postData = new StringBuilder();
                for (Map.Entry<String,Object> param : params.entrySet()) {
                    if (postData.length() != 0) postData.append('&');
                    postData.append(URLEncoder.encode(param.getKey(), "UTF-8"));
                    postData.append('=');
                    postData.append(URLEncoder.encode(String.valueOf(param.getValue()), "UTF-8"));
                }
                byte[] postDataBytes = postData.toString().getBytes(StandardCharsets.UTF_8);


                conn = (HttpURLConnection) url.openConnection();
                conn.setReadTimeout(READ_TIMEOUT);
                conn.setConnectTimeout(CONNECTION_TIMEOUT);
                conn.setRequestMethod("POST");
                conn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded;  charset=UTF-8");
                conn.setRequestProperty("Content-Length", String.valueOf(postDataBytes.length));
                conn.setRequestProperty("AppToken", STOREDTOKEN);

                // setDoOutput to true as we recieve data from json file
                conn.setDoInput(true);
                conn.setDoOutput(true);
                conn.setUseCaches(false);
                conn.getOutputStream().write(postDataBytes);


            } catch (IOException e1) {

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
                editJobDetail.setText("");
                editJobRemarks.setText("");
                editJobTime.setText("");
                editJobDate.setText("");
                new FetchMyJobHistory().execute();
                AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
                builder.setMessage(requireActivity().getResources().getString(R.string.Dialog_Label_AddJobSuccess))
                        .setCancelable(false)
                        .setPositiveButton(requireActivity().getResources().getString(R.string.Dialog_Label_OK), new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {

                            }
                        });
                AlertDialog alert = builder.create();
                alert.show();

            }else{

                pdLoading.dismiss();
                Toast.makeText(requireActivity(), requireActivity().getResources().getString(R.string.Toast_Label_Failed), Toast.LENGTH_LONG).show();

                AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
                builder.setMessage(requireActivity().getResources().getString(R.string.Dialog_Label_AddJobFailed))
                        .setCancelable(false)
                        .setPositiveButton(requireActivity().getResources().getString(R.string.Dialog_Label_OK), new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {

                            }
                        });
                AlertDialog alert = builder.create();
                alert.show();
            }



        }

    }




    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View v = inflater.inflate(R.layout.frag_enrolment, container, false);

        editJobDetail = v.findViewById(R.id.InputJobDetail);
        editJobRemarks = v.findViewById(R.id.InputJobRemarks);
        editJobTime = v.findViewById(R.id.InputJobWorkingTime);
        editJobDate = v.findViewById(R.id.InputJobDate);
        Button buttonSubmit;
        buttonSubmit = v.findViewById(R.id.btnSubmit);
        buttonSubmit.setOnClickListener(new View.OnClickListener() { // calling onClick() method
            @Override
            public void onClick(View v) {
                new PostJobDetail().execute();
            }
        });

        new FetchMyJobHistory().execute();
        RVMyjoblist = v.findViewById(R.id.MyJobHistory);
        RVMyjoblist .setAdapter(mjhAdapter);
        RVMyjoblist .setLayoutManager(new LinearLayoutManager(getActivity()));


        return v;
    }
}
