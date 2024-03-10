#include <iostream>
#include "index.h"
#include <vector>
#include <fstream>
#include <sstream>
#include <iomanip>
#include <algorithm>
using namespace std;

Node::Node()
{
	//dynamic memory allocation
	key = new int[MAX];
	ptr = new Node*[MAX + 1];
	value = new int[MAX];

	for(int i = 0; i < MAX + 1; i ++)
    {
        ptr[i] = NULL;
    }
}


Index::Index(int num_rows, vector<int> key, vector<int> value)
{
	for(int i = 0; i < num_rows; i ++)
    {
        insert(key[i], value[i]);
    }
}

void Index::insert(int key, int value)
{
	//insert logic
	if(root == NULL)
	{
		root = new Node;
		root->key[0] = key;
		root->value[0] = value;
		root->IS_LEAF = true;
		root->size = 1;
	}
	else
	{
		Node* cursor = root;
		Node* parent;
		//in the following while loop, cursor will travel to the leaf node possibly consisting the key
		while(cursor->IS_LEAF == false)
		{
			parent = cursor;
			for(int i = 0; i < cursor->size; i ++)
			{
				if(key < cursor->key[i])
				{
					cursor = cursor->ptr[i];//down
					break;
				}
				if(i == cursor->size - 1)
				{
					cursor = cursor->ptr[i + 1];
					break;
				}
			}
		}
		//now cursor is the leaf node in which we'll insert the new key
		if(cursor->size < cursor->MAX)//if cursor is not full
		{
			//find the correct position for new key
			int i = 0;
			while(key > cursor->key[i] && i < cursor->size)
            {
                i ++;
            }
			//make space for new key
			for(int j = cursor->size; j > i; j --)
			{
				cursor->key[j] = cursor->key[j - 1];
				cursor->value[j] = cursor->value[j - 1];
			}
			cursor->key[i] = key;
			cursor->value[i] = value;
            cursor->size = cursor->size + 1;
			cursor->ptr[cursor->size] = cursor->ptr[cursor->size - 1];
			cursor->ptr[cursor->size - 1] = NULL;
		}
		else//overflow condition
		{
			//create new leaf node
			Node* newLeaf = new Node;
			//
            int* virtual_key = new int[cursor->MAX + 1];
            int* virtual_value = new int[cursor->MAX + 1];
			for(int i = 0; i < cursor->MAX; i ++)
			{
				virtual_key[i] = cursor->key[i];
				virtual_value[i] = cursor->value[i];
			}
			int i = 0;
			/*while(key > virtual_key[i] && i < cursor->MAX)
            {
                i ++;
            }*/
            //
            for(i = 0; i < cursor->size; i ++)//find a position for key
            {
                if(key < virtual_key[i])
                {
                    break;
                }
            }
            //
			//make space for new key
			for(int j = cursor->MAX; j > i; j --)
			{
				virtual_key[j] = virtual_key[j - 1];
				virtual_value[j] = virtual_value[j - 1];
			}
			virtual_key[i] = key;
			virtual_value[i] = value;
			newLeaf->IS_LEAF = true;
			//split the cursor into two leaf nodes
			cursor->size = (cursor->MAX + 1) / 2;

			newLeaf->size = cursor->MAX + 1 - cursor->size;//size more 1

			//make cursor point to new leaf node
			cursor->ptr[cursor->size] = newLeaf;//point to right
			//make new leaf node point to the next leaf node
			//point to cursor's original neighbor
			newLeaf->ptr[newLeaf->size] = cursor->ptr[cursor->MAX];
			cursor->ptr[cursor->MAX] = NULL;
			//now give elements to new leaf nodes


			for(int j = 0; j < cursor->size; j ++)
			{
				cursor->key[j] = virtual_key[j];
				cursor->value[j] = virtual_value[j];
			}

            int j = cursor->size;
            for(int k = 0; k < newLeaf->size; k ++)
            {
                newLeaf->key[k] = virtual_key[j];
                newLeaf->value[k] = virtual_value[j];
                j ++;
            }
			//
			//create a virtual node and insert x into it


			//modify the parent
			if(cursor == root)//put middle one key up
			{
				//if cursor is a root node, we create a new root
				Node* newRoot = new Node;
				newRoot->key[0] = newLeaf->key[0];
				newRoot->value[0] = newLeaf->value[0];
				newRoot->ptr[0] = cursor;
				newRoot->ptr[1] = newLeaf;
				newRoot->IS_LEAF = false;
				newRoot->size = 1;//newLeaf->key[0] just one key
				root = newRoot;
			}
			else//put one key up
			{
				//insert new key in parent node
				insertInternal(newLeaf->key[0], parent, newLeaf, newLeaf->value[0]);
			}//need newleaf, because "the one key up" need to point to newleaf
			delete[] virtual_key;
			delete[] virtual_value;
		}
	}
}

void Index::insertInternal(int key, Node* cursor, Node* child, int value)
{
	if(cursor->size < cursor->MAX)
	{
		//if parent is not full

		int i = 0;
		/*while(key > cursor->key[i] && i < cursor->size)
        {
            i ++;
        }*/

        for(i = 0; i < cursor->size; i ++)//find the correct position for new key
        {
            if(key < cursor->key[i])//find the first > key's number, and we want that position
            {
                break;
            }
        }

		//make space for new key
		for(int j = cursor->size; j > i; j --)
		{
			cursor->key[j] = cursor->key[j - 1];
			cursor->value[j] = cursor->value[j - 1];
			cursor->ptr[j + 1] = cursor->ptr[j];//move the pointer which point to child
		}//make space for new pointer
		cursor->key[i] = key;
		cursor->value[i] = value;
		cursor->size = cursor->size + 1;
		cursor->ptr[i + 1] = child;
	}
	else//if overflow in internal node
		//create new internal node
	{
		//
		Node* newInternal = new Node;
		//create virtual Internal Node;
        int* virtual_key = new int[cursor->MAX + 1];
        int* virtual_value = new int[cursor->MAX + 1];
		Node** virtualPtr = new Node* [cursor->MAX + 2];
		for(int i = 0; i < cursor->MAX; i ++)
		{
			virtual_key[i] = cursor->key[i];
			virtual_value[i] = cursor->value[i];
			virtualPtr[i] = cursor->ptr[i];
		}
		virtualPtr[cursor->MAX] = cursor->ptr[cursor->MAX];

		int i = 0;
		/*while(key > virtual_key[i] && i < cursor->MAX)
        {
            i ++;
        }*/

        for(i = 0; i < cursor->size; i ++)//find the correct position for new key   size=MAX
        {
            if(key < virtual_key[i])//find the first > key's number, and we want that position
            {
                break;
            }
        }
		//make space for new ptr
		for(int j = cursor->size; j > i; j --)
		{
			virtual_key[j] = virtual_key[j - 1];
			virtual_value[j] = virtual_value[j - 1];
			virtualPtr[j + 1] = virtualPtr[j];
		}
		virtual_key[i] = key;
		virtual_value[i] = value;
		virtualPtr[i + 1] = child;//(the middle one up)need to point to newleaf
		newInternal->IS_LEAF = false;
		//split cursor into two nodes
		cursor->size = (cursor->MAX + 1) / 2;
		newInternal->size = cursor->MAX - cursor->size;
		//give elements and pointers to the new node
		for(int i = 0; i < cursor->size; i ++)
		{
			cursor->key[i] = virtual_key[i];
			cursor->value[i] = virtual_value[i];
			cursor->ptr[i] = virtualPtr[i];
		}

		cursor->ptr[cursor->size] = virtualPtr[cursor->size];// equal to old cursor->ptr[cursor->MAX]

        int j = cursor->size + 1;
		for(int i = 0; i < newInternal->size; i ++)
		{
			newInternal->key[i] = virtual_key[j];
			newInternal->value[i] = virtual_value[j];
			newInternal->ptr[i] = virtualPtr[j];
            j ++;
		}
		newInternal->ptr[newInternal->size] = virtualPtr[cursor->MAX + 1];
		if(cursor == root)//push middle one up
		{
			//if cursor is a root node, we create a new root
			Node* newRoot = new Node;
			newRoot->key[0] = virtual_key[cursor->size];
			newRoot->value[0] = virtual_value[cursor->size];
			newRoot->ptr[0] = cursor;
			newRoot->ptr[1] = newInternal;
			newRoot->IS_LEAF = false;
			newRoot->size = 1;
			root = newRoot;
		}
		else
		{
			//recursion
			//find depth first search to find parent of cursor
			insertInternal(virtual_key[cursor->size], findParent(root, cursor), newInternal, virtual_value[cursor->size]);
		}
		delete[] virtual_key;
		delete[] virtual_value;
		delete[] virtualPtr;
		//

	}
}

Node* Index::findParent(Node* cursor, Node* child)
{
	//finds parent using depth first traversal and ignores leaf nodes as they cannot be parents
	Node* parent;
	if(cursor->IS_LEAF == true)//leaf can not be a parent
	{
		return NULL;
	}
    else
    {
        for(int i = 0; i < cursor->size + 1; i ++)
        {
            if(cursor->ptr[i] == child)
            {
                return cursor;
            }
            else
            {
                parent = findParent(cursor->ptr[i], child);
                if(parent != NULL)
                {
                    return parent;
                }
            }
        }
        return parent;
    }
}

void Index::key_query(vector<int> keys)
{
	fstream key_query_out_file;
	key_query_out_file.open("key_query_out.txt", ios::out|ios::trunc);//ios::out means output
                                                        //ios::trunc means if file exited, clean old data

	for(int i = 0; i < keys.size(); i ++)
	{
		Node* cursor = root;
		//in the following while loop, cursor will travel to the leaf node possibly consisting the key
		while(cursor->IS_LEAF == false)
		{
			for(int j = 0; j < cursor->size; j ++)
			{
				if(keys[i] < cursor->key[j])//go down to child
				{
					cursor = cursor->ptr[j];
					break;
				}
				if(j == cursor->size - 1)//go down to child
				{
					cursor = cursor->ptr[j + 1];
					break;
				}
			}
		}
		//in the following for loop, we search for the key if it exists

		int flag = 0;
		for(int j = 0; j < cursor->size; j ++)
		{
			if(cursor->key[j] == keys[i])
			{
				key_query_out_file << cursor->value[j] << "\n";
				flag = 1;
				break;
			}
		}
		if(flag == 0)
		{
			key_query_out_file << -1 << "\n";
		}
	}
	key_query_out_file.close();

}

void Index::range_query(vector<pair<int,int> > keyr)
{
	fstream range_query_out_file;
	range_query_out_file.open("range_query_out.txt", ios::out|ios::trunc);
	for(int i = 0; i < keyr.size(); i ++)
    {
        int left = keyr[i].first;
        int right = keyr[i].second;
        int maxi = -1;
        Node* cursor = root;

        /*int now = lower_bound(cursor->key, cursor->key + cursor->size, left) - cursor->key;//start
        //in the following while loop, now_node will travel to the leaf node possibly consisting the key
        while(cursor->IS_LEAF == false)//go deep
        {
            //start = lower_bound(cursor->key, cursor->key + cursor->size, left) - cursor->key;//return first number's position which > left
            //start from position cursor->key, end to cursor->key + cursor->size //use in array
            cursor = cursor->ptr[now];//go down
            now = lower_bound(cursor->key, cursor->key + cursor->size, left) - cursor->key;
        }*/

        //
        int now;
        while(cursor->IS_LEAF == false)//find leaf node which has key <= left
		{
			//parent = cursor;
			for(now = 0; now < cursor->size; now ++)
			{
				if(left < cursor->key[now])
				{
					cursor = cursor->ptr[now];//down
					break;
				}
				if(now == cursor->size - 1)
				{
					cursor = cursor->ptr[now + 1];
					break;
				}
			}
		}
		int h = 0;
		for(now = 0; now < cursor->size; now ++)//find where is "left" in the leaf
        {
            if(left <= cursor->key[now])
            {
                h = 1;
                break;
            }
        }
        if(h == 0)//see ipad:all key in leaf > left
        {
            if(cursor->ptr[cursor->size] != NULL)//right side node is not null
            {
                now = 0;
                cursor = cursor->ptr[cursor->size];
            }
        }
        //


        //int next = /*lower_bound(cursor->key, cursor->key + cursor->size, left) - cursor->key*/start;//l's next key
            //return first number's position which >= left
        /*if(now == cursor->size)//all key in this node < next, turn right
        {
            if(cursor->ptr[cursor->size] != NULL)//right side node is not null
            {
                now = 0;
                cursor = cursor->ptr[cursor->size];
            }
        }*/

        int has = 0;
        while(cursor->key[now] <= right)
        {
            if(maxi < cursor->value[now])
            {
                maxi = cursor->value[now];
            }
            if(now == cursor->size - 1)//because j start from 0
            {
                if(cursor->ptr[now + 1] == NULL)//no right side
                {
                    range_query_out_file << maxi << "\n";
                    has = 1;
                    break;
                }
                else
                {
                    cursor = cursor->ptr[now + 1];//go to next right side leaf
                    now = 0;
                }
            }
            else
            {
                now ++;
            }
        }
        if(has == 0)//maxima num
        {
            range_query_out_file << maxi << "\n";
        }

	}

	range_query_out_file.close();
}

void Index::cleanUp(Node* cursor)
{
	//clean up logic
	if(cursor != NULL)
	{
		if(cursor->IS_LEAF == false)
		{
			for(int i = 0; i < cursor->size + 1; i ++)
			{
				cleanUp(cursor->ptr[i]);
			}
		}
        //start delete from leaf node
		delete[] cursor->key;
		delete[] cursor->value;
		delete[] cursor->ptr;
		delete cursor;
	}
}

void Index::clear_index()
{
	//calling cleanUp routine
	cleanUp(root);
}
