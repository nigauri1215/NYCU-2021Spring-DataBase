#include <iostream>
#include <vector>
#include <iomanip>

using namespace std;

class Node {
public:
    bool is_leaf;       //是否為leaf node
    int* key, size;     //存放索引，node size
    Node** ptr;         //pointer用來指向連結的node
    int m;              //存input m
    friend class BPtree;

    Node(int max);
};

class BPtree {
public:
    Node* root;         
    void insert_internal(int, int, Node*, Node*);
    Node* find_parent(Node*, Node*);
    int m;
    BPtree(int max);

    void search(int);
    void insert(int);
    void display(Node*, int);
    void seq_access(int, int);
    Node* getroot();
};

Node::Node(int max) {
    m = max;
    key = new int[m];           //最多有m個key
    ptr = new Node * [m + 1];   //最多有m+1個pointer

    for (int i = 0; i < m + 1; i++)
    {
        ptr[i] = NULL;
    }
}

BPtree::BPtree(int max) {
    m = max;
    root = NULL;
}

//search
void BPtree::search(int x) {
    if (root == NULL) {
        cout << "QAQ\n";
    }
    else {
        Node* cursor = root;
        Node* temp = cursor;
        while (cursor->is_leaf == false) {
            cout << "(";
            bool find = false;
            for (int i = 0; i < cursor->size; i++) {
                cout << cursor->key[i];
                if (x < cursor->key[i]&&!find) {   //x<ki 找i+1左邊的值
                    find = true;
                    temp = cursor->ptr[i];
                    
                }
                if (i == cursor->size - 1) {    //x找到最右邊了
                    cout << ")\n";
                    if(!find)
                        temp = cursor->ptr[i + 1];
                    break;
                }
                else {
                    cout << " ";
                }
            }
            cursor = temp;
        }
        cout << "(";
        for (int k = 0; k < cursor->size - 1; k++) {
            cout << cursor->key[k] << " ";
        }
        cout << cursor->key[cursor->size - 1] << ")\n";
        for (int i = 0; i < cursor->size; i++) {

            if (cursor->key[i] == x) {
                cout << "Found\n";
                return;
            }
        }
        cout << "QAQ\n";
    }
}

void BPtree::insert(int x) {
    //如果tree是空的，先設成root
    if (root == NULL) {
        root = new Node(m);
        root->key[0] = x;
        root->is_leaf = true;
        root->size = 1;
        
    }
    else {
        Node* cursor = root;
        Node* parent = cursor;      //設一個pointer cursor用來追蹤目前的位置
        //while迴圈用來找value應該insert的位置在哪個區段
        while (cursor->is_leaf == false) {
            parent = cursor;
            for (int i = 0; i < cursor->size; i++) {
                if (x < cursor->key[i]) {
                    cursor = cursor->ptr[i];
                    break;
                }
                if (i == cursor->size - 1) {
                    cursor = cursor->ptr[i + 1];
                    break;
                }
            }
        }
        
        
        if (cursor->size < m) {     //node is not full
            int i = 0;
            //找可以insert new key的地方
            while (x > cursor->key[i] && i < cursor->size) {
                i++;
            }
            //騰出空間給new key，全部右移一格
            for (int j = cursor->size; j > i; j--) {
                cursor->key[j] = cursor->key[j - 1];
            }
            //insert key:更新value，size，link list
            cursor->key[i] = x;
            cursor->size++;
            cursor->ptr[cursor->size] = cursor->ptr[cursor->size - 1];
            cursor->ptr[cursor->size - 1] = NULL;
        }
        else {      //node is full,need split
            //設一個virtual Node暫存data，newleaf準備存新的data
            Node* newleaf = new Node(m);
            vector<int>virtualNode(m + 1);
            for (int i = 0; i < m; i++) {
                virtualNode[i] = cursor->key[i];        //先copy一份
            }

            int i = 0, j;
            //找可以insert new key的地方
            for (i = 0; i < cursor->size; i++) {
                if (x < virtualNode[i])
                    break;
            }
            //騰出空間給new key
            for (int j = m; j > i; j--) {
                virtualNode[j] = virtualNode[j - 1];
            }
            virtualNode[i] = x;

            //cursor為分開後左邊的node，newleaf為右邊
            newleaf->is_leaf = true;
            cursor->size = (m + 1) / 2;
            newleaf->size = (m + 1) - cursor->size;

            cursor->ptr[cursor->size] = newleaf;    //連接新node ()->()
            newleaf->ptr[newleaf->size] = cursor->ptr[m];   //新的右邊的node接到舊的link list
            cursor->ptr[m] = NULL;


            for (i = 0; i < cursor->size; i++) {
                cursor->key[i] = virtualNode[i];
            }
            //全部copy給new node
            int q = cursor->size;
            for (int k = 0; k < newleaf->size; k++) {
                newleaf->key[k] = virtualNode[q];
                q++;
            }

            //modify the parent
            if (cursor == root) {   //如果本來就是root的話
                Node* newroot = new Node(m);
                newroot->key[0] = newleaf->key[0];
                newroot->ptr[0] = cursor;
                newroot->ptr[1] = newleaf;
                newroot->is_leaf = false;
                newroot->size = 1;
                root = newroot;
            }
            else {
                //修改nonleaf node，丟key值上去
                insert_internal(newleaf->key[0], m, parent, newleaf);
            }

        }
    }
}

void BPtree::insert_internal(int x, int m, Node* cursor, Node* child) {
    //insert new key
    if (cursor->size < m) {     //node is not full
        int i = 0;
        for (i = 0; i < cursor->size; i++)
        {
            if (x < cursor->key[i])
            {
                break;
            }
        }
        //key pointer移出空間
        for (int j = cursor->size; j > i; j--) {
            cursor->key[j] = cursor->key[j - 1];
            cursor->ptr[j + 1] = cursor->ptr[j];
        }
        //更新資料，接新的node
        cursor->key[i] = x;
        cursor->size++;
        cursor->ptr[i + 1] = child;
    }
    else {      //node is full
        //設newinternal為split後的node，virtual key和virtual pointer暫存
        Node* newinternal = new Node(m);
        vector<int>virtualkey(m + 1);
        vector<Node*>virtualPtr(m + 2);
        for (int i = 0; i < m; i++) {
            virtualkey[i] = cursor->key[i];
        }
        for (int i = 0; i < m + 1; i++) {
            virtualPtr[i] = cursor->ptr[i];
        }
        int i = 0, j;
        for (i = 0; i < cursor->size; i++)      //find correct position
        {
            if (x < virtualkey[i])
            {
                break;
            }
        }
        //移開value pointer
        for (int j = cursor->size; j > i; j--) {
            virtualkey[j] = virtualkey[j - 1];
            virtualPtr[j + 1] = virtualPtr[j];
        }
        virtualkey[i] = x;
        virtualPtr[i + 1] = child;  //接上new child
        newinternal->is_leaf = false;
        //split
        cursor->size = (m + 1) / 2;
        newinternal->size = m- cursor->size;
        //放入資料到左邊的node
        for (int i = 0; i < cursor->size; i++)
        {
            cursor->key[i] = virtualkey[i];
            cursor->ptr[i] = virtualPtr[i];
        }
        cursor->ptr[cursor->size] = virtualPtr[cursor->size];   
        
        //除了中間要push up 的元素，其他都copy到node裡
        j = cursor->size + 1;
        for (i = 0; i < newinternal->size; i++) {
            newinternal->key[i] = virtualkey[j];
            j++;
        }
        j = cursor->size + 1;
        for (i = 0; i < newinternal->size + 1; i++) {
            newinternal->ptr[i] = virtualPtr[j];
            j++;
        }

        //push middle one up
        if (cursor == root) {
            Node* newroot = new Node(m);
            newroot->key[0] = virtualkey[cursor->size];
            newroot->ptr[0] = cursor;
            newroot->ptr[1] = newinternal;
            newroot->is_leaf = false;
            newroot->size = 1;
            root = newroot;
        }
        else {  //如果還沒到root node就繼續往上丟key
            insert_internal(virtualkey[cursor->size], m, find_parent(root, cursor), newinternal);
        }

    }
}

Node* BPtree::find_parent(Node* cursor, Node* child) {
    Node* parent = NULL;
    //leaf can not be a parent
    if (cursor->is_leaf || (cursor->ptr[0])->is_leaf) {
        return NULL;
    }
    for (int i = 0; i < cursor->size + 1; i++) {
        if (cursor->ptr[i] == child) {
            parent = cursor;
            return parent;
        }
        else {
            parent = find_parent(cursor->ptr[i], child);
            if (parent != NULL)
                return parent;
        }
    }
    return parent;
}

void BPtree::display(Node* cursor, int count) {
    if (cursor != NULL) {
        for (int s = 0; s < count; s++) {
            cout << "  ";
        }
        cout << "(";
        for (int i = 0; i < cursor->size; i++) {
            cout << cursor->key[i];
            if (i != cursor->size - 1)
                cout << " ";
        }
        cout << ")\n";
        if (!cursor->is_leaf) {
            count++;
            for (int i = 0; i < cursor->size + 1; i++) {
                display(cursor->ptr[i], count);
            }
        }
    }
    else {
        cout << "()" << endl;
    }
}
void BPtree::seq_access(int x, int n) {
    int cnt = 0;
    if (root == NULL) {
        cout << "Access Failed\n" << endl;
    }
    else {
        Node* cursor = root;
        while (cursor->is_leaf == false) {
            for (int i = 0; i < cursor->size; i++) {
                if (x < cursor->key[i]) {
                    cursor = cursor->ptr[i];
                    break;
                }
                if (i == cursor->size - 1) {
                    cursor = cursor->ptr[i + 1];
                    break;
                }
            }
        }
        bool found = false;
        int now;
        for (int i = 0; i < cursor->size; i++) {

            if (cursor->key[i] == x) {
                found = true;
                now = i;
            }
        }
        while (found) {
            cout << cursor->key[now];
            cnt++;
            if (now == cursor->size - 1) {
                cursor = cursor->ptr[now+1];
                now = 0;
            }
            else {
                now++;
            }
            if (cursor == NULL || cnt==n)
                break;
            cout << " ";
        }
        cout << "\n";
        if (cnt < n && found) {
            cout << "N is too large\n";
        }
        else if (!found) {
            cout<< "Access Failed\n";
        }
    }
}

Node* BPtree::getroot() {
    return root;
}

int main()
{
    int m;
    char op;
    cin >> m;
    BPtree node(m-1);
    while (cin >> op && op != 'q') {
        if (op == 'p') {
            int c = 0;
            node.display(node.getroot(), c);
            cout << endl;
        }
        if (op == 'i') {
            int v;
            cin >> v;
            node.insert(v);
        }
        if (op == 's') {
            int v;
            cin >> v;
            node.search(v);
            cout << endl;

        }
        if (op == 'a') {
            int x,n;
            cin >> x >> n;
            node.seq_access(x,n);
            cout << endl;
        }
    }
}
