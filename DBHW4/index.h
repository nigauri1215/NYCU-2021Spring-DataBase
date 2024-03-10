#include <iostream>
#include <vector>
using namespace std;
class Index;

class Node
{
  bool IS_LEAF;
  int *key;
  int *value;
  int size;
  int MAX = 100;
  Node** ptr;
  friend class Index;
public:
	Node();
};

class Index
{
  Node *root = NULL;
  void insertInternal(int, Node*, Node*, int);
  Node* findParent(Node*, Node*);

public:
  Index(int, vector<int>, vector<int>);
  void insert(int, int);  //insert
  void range_query(vector<pair<int,int>>);  //search
  void key_query(vector<int>);
  void cleanUp(Node*);
  void clear_index();
};
