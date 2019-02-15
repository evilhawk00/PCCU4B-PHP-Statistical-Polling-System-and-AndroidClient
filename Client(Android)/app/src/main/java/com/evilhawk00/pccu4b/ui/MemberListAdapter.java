package com.evilhawk00.pccu4b.ui;

import android.content.Context;
import android.support.v4.content.ContextCompat;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import com.bumptech.glide.Glide;
import com.bumptech.glide.request.RequestOptions;
import com.evilhawk00.pccu4b.R;

import java.util.Collections;
import java.util.List;

public class MemberListAdapter extends RecyclerView.Adapter<RecyclerView.ViewHolder> {

    private Context context;
    private LayoutInflater inflater;
    List<MemberData> data= Collections.emptyList();
    //MemberData current;
    //int currentPos=0;

    // create constructor to innitilize context and data sent from MainActivity
    public MemberListAdapter(Context context, List<MemberData> data){
        this.context=context;
        inflater= LayoutInflater.from(context);
        this.data=data;
    }

    // Inflate the layout when viewholder created
    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view=inflater.inflate(R.layout.container_memberlist, parent,false);
        //MyHolder holder=new MyHolder(view);
        return new MyHolder(view);
    }

    // Bind data
    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder holder, int position) {



        // Get current position of item in recyclerview to bind data and assign values from list
        MyHolder myHolder= (MyHolder) holder;
        MemberData current=data.get(position);
        myHolder.textMemberName.setText(current.DISPLAYNAME);
        myHolder.textTotalWorkTime.setText(context.getResources().getString(R.string.MemberList_Label_TotalTimeMinutes,current.TOTALWORKTIME));
        myHolder.textNickName.setText( context.getResources().getString(R.string.MemberList_Label_NickName,current.NICKNAME));

        //CHECK IF LASTLOGIN HAS VALUE "NULL"(Check with ignore case)
        if(current.LASTLOGIN.equalsIgnoreCase("NULL")){
            //null found at lastlogin data, means never logged in
            myHolder.textLastLogin.setText(context.getResources().getString(R.string.MemberList_Label_NoLoginRecord));

        }else{
            //null not found, print the date
            myHolder.textLastLogin.setText( current.LASTLOGIN );

        }

        myHolder.textLastLogin.setTextColor(ContextCompat.getColor(context, R.color.LastLogin));

        // load image into imageview using glide
        Glide.with(context).load(R.drawable.default_avatar)
                .apply(new RequestOptions().placeholder(R.drawable.default_avatar).error(R.drawable.default_avatar))
                .into(myHolder.avatar);

    }

    // return total item from List
    @Override
    public int getItemCount() {
        return data.size();
    }


    class MyHolder extends RecyclerView.ViewHolder{

        TextView textMemberName;
        ImageView avatar;
        TextView textTotalWorkTime;
        TextView textNickName;
        TextView textLastLogin;

        // create constructor to get widget reference
        public MyHolder(View itemView) {
            super(itemView);
            textMemberName = itemView.findViewById(R.id.tMemberName);
            avatar = itemView.findViewById(R.id.avatarIcon);
            textTotalWorkTime = itemView.findViewById(R.id.tTotalWorkTime);
            textNickName = itemView.findViewById(R.id.tNickName);
            textLastLogin = itemView.findViewById(R.id.tLastLogin);
        }

    }

}
