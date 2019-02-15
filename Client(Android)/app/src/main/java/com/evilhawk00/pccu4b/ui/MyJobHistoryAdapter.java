package com.evilhawk00.pccu4b.ui;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.evilhawk00.pccu4b.R;

import java.util.Collections;
import java.util.List;

public class MyJobHistoryAdapter extends RecyclerView.Adapter<RecyclerView.ViewHolder> {

    private Context context;
    private LayoutInflater inflater;
    List<MyJobHistoryData> data= Collections.emptyList();
    //MyJobHistoryData current;
    //int currentPos=0;

    // create constructor to innitilize context and data sent from MainActivity
    public MyJobHistoryAdapter(Context context, List<MyJobHistoryData> data){
        this.context=context;
        inflater= LayoutInflater.from(context);
        this.data=data;
    }

    // Inflate the layout when viewholder created
    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view=inflater.inflate(R.layout.container_myjobhistory, parent,false);
        //MyHolder holder=new MyHolder(view);
        return new MyHolder(view);
    }

    // Bind data
    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder holder, int position) {

        // Get current position of item in recyclerview to bind data and assign values from list
        MyHolder myHolder= (MyHolder) holder;
        MyJobHistoryData current=data.get(position);
        myHolder.textJobTime.setText(context.getResources().getString(R.string.JobList_Label_Minutes,current.JOBTIME));
        myHolder.textJobDetail.setText( current.JOBDETAIL);
        myHolder.textJobDate.setText(context.getResources().getString(R.string.JobList_Label_Date,current.JOBDATE));


    }

    // return total item from List
    @Override
    public int getItemCount() {
        return data.size();
    }


    class MyHolder extends RecyclerView.ViewHolder{

        TextView textMemberName;

        TextView textJobTime;
        TextView textJobDetail;
        TextView textJobDate;

        // create constructor to get widget reference
        public MyHolder(View itemView) {
            super(itemView);
            textMemberName = itemView.findViewById(R.id.tMemberName);

            textJobTime = itemView.findViewById(R.id.tJobTime);
            textJobDetail = itemView.findViewById(R.id.tJobDetail);
            textJobDate = itemView.findViewById(R.id.tJobDate);
        }

    }

}

